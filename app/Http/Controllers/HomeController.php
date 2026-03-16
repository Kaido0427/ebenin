<?php

namespace App\Http\Controllers;

use App\Models\organization;
use App\Models\post;
use App\Models\rubrique;
use App\Models\User;
use App\Models\publicite;
use App\Models\userOrganization;
use App\Models\organization_social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    // ─────────────────────────────────────────────
    //  Helpers privés
    // ─────────────────────────────────────────────

    private function getSubdomain(): ?string
    {
        $parts = explode('.', request()->getHost());
        return count($parts) > 2 ? $parts[0] : null;
    }

    private function getBaseDomain(Request $request): string
    {
        return str_contains($request->getHost(), 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
    }

    // ─────────────────────────────────────────────
    //  Pages publiques
    // ─────────────────────────────────────────────

    public function index()
    {
        $subdomain = $this->getSubdomain();

        $organization = Organization::where('subdomain', $subdomain)->firstOrFail();

        $user = Auth::user();

        $rubriques = Rubrique::whereHas('posts', function ($q) use ($organization) {
            $q->whereHas('user', fn($q2) => $q2->where('organization_id', $organization->id));
        })->get();

        // Un post aléatoire (sans vidéo) par rubrique
        $randomPosts = $rubriques->map(function ($rubrique) use ($organization) {
            $posts = $rubrique->posts->filter(
                fn($p) => $p->user->organization_id === $organization->id && is_null($p->video)
            );
            return $posts->isNotEmpty()
                ? ['rubrique' => $rubrique, 'post' => $posts->random()]
                : null;
        })->filter()->values();

        // Dernière actualité (sans vidéo)
        $latestNews = $rubriques->flatMap(function ($rubrique) use ($organization) {
            return $rubrique->posts->filter(
                fn($p) => $p->user->organization_id === $organization->id && is_null($p->video)
            );
        })->sortByDesc('created_at')->first();

        // Posts à la une
        $featuredPosts = Post::where('featured', 1)
            ->whereHas('user', fn($q) => $q->where('organization_id', $organization->id))
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        // Reportages (vidéos ou rubrique "reportage")
        $reportages = Post::whereIn('user_id', $organization->users->pluck('id'))
            ->where(
                fn($q) => $q
                    ->whereNotNull('video')
                    ->orWhereHas('rubriques', fn($q2) => $q2->where('name', 'reportage'))
            )
            ->with('user', 'rubriques', 'comments')
            ->orderByDesc('created_at')
            ->get();

        $randomTags = Rubrique::inRandomOrder()->take(10)->get();
        $socials     = organization_ocial::where('organization_id', $organization->id)->get();
        $pub         = Publicite::where('space', 'blog.e-benin')->first();

        return view('myBlog.index', compact(
            'organization',
            'pub',
            'user',
            'socials',
            'rubriques',
            'randomPosts',
            'latestNews',
            'featuredPosts',
            'randomTags',
            'subdomain',
            'reportages'
        ));
    }

    public function navbar()
    {
        $rubriques             = Rubrique::whereHas('posts')->with('posts')->get();
        $latestPosts           = Post::whereNull('video')->orderByDesc('created_at')->take(15)->get();
        $flashNews             = Post::orderByDesc('created_at')->take(4)->get();
        $newPosts              = Post::whereNull('video')->orderByDesc('created_at')->take(4)->get();
        $featuredPosts         = Post::where('featured', 1)->orderByDesc('created_at')->get();
        $rubriquesWithoutPosts = Rubrique::all();
        $reportages            = Post::whereNotNull('video')->with('user', 'rubriques', 'comments')->get();
        $tags                  = Rubrique::all();
        $pub                   = Publicite::where('space', 'e-benin')->first();
        $footerOrgs            = Organization::whereHas('users.posts')->get();

        $randomizedPosts = [];
        foreach (Organization::with(['users.posts'])->get() as $org) {
            $subdomain = urlencode($org->subdomain);
            foreach ($org->users as $u) {
                foreach ($u->posts as $post) {
                    $randomizedPosts[] = ['organization' => $subdomain, 'post' => $post];
                }
            }
        }
        shuffle($randomizedPosts);

        return view('index', compact(
            'rubriques',
            'pub',
            'footerOrgs',
            'flashNews',
            'latestPosts',
            'newPosts',
            'featuredPosts',
            'rubriquesWithoutPosts',
            'randomizedPosts',
            'tags',
            'reportages'
        ));
    }

    public function showUserRubrique(string $subdomain, int $id)
    {
        $organization = Organization::where('subdomain', $subdomain)->firstOrFail();
        $rubrique     = Rubrique::with('posts')->findOrFail($id);

        $posts          = $rubrique->posts->filter(fn($p) => $p->user->organization->id === $organization->id);
        $paginatedPosts = $this->paginatePosts($posts, 'organization');
        $rubriquesGuest = Rubrique::whereHas('posts')->get();

        return view('myBlog.category', compact('rubrique', 'paginatedPosts', 'rubriquesGuest', 'organization'));
    }

    public function allCategories(int $id)
    {
        $rubrique       = Rubrique::with('posts')->findOrFail($id);
        $paginatedPosts = $this->paginatePosts($rubrique->posts, 'user');
        $rubriquesGuest = Rubrique::whereHas('posts')->get();

        return view('allcats', compact('rubrique', 'paginatedPosts', 'rubriquesGuest'));
    }

    public function userRegisterView()
    {
        return view('register', ['rubriquesWithoutPosts' => Rubrique::all()]);
    }

    // ─────────────────────────────────────────────
    //  Authentification
    // ─────────────────────────────────────────────

    public function userLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Identifiants incorrects.'])->withInput();
        }

        // Vérification abonnement
        $expiry = ($user->subscription_started_at && $user->subscription_quantity)
            ? $user->subscription_started_at->copy()->addMonths($user->subscription_quantity)
            : null;

        if (!$expiry || now()->greaterThan($expiry)) {
            Log::warning('Login refuse : abonnement expire', ['user_id' => $user->id]);

            // ✅ On redirige vers le sous-domaine de l'user (pas route('subscription')
            //    qui exige le parametre {organization} et plante sur le domaine principal)
            $baseDomain = $this->getBaseDomain($request);
            $subdomain  = $user->organization?->subdomain;

            if ($subdomain) {
                return redirect()->to("https://{$subdomain}.{$baseDomain}/subscription")
                    ->with('error', 'Votre abonnement a expire. Veuillez le renouveler.');
            }

            // Fallback si pas d'organisation
            return back()->with('error', 'Votre abonnement a expire. Contactez le support.');
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return back()->withErrors(['email' => 'Identifiants incorrects.'])->withInput();
        }

        $user = Auth::user();

        if (empty($user->organization?->subdomain)) {
            Auth::logout();
            return back()->with('error', 'Organisation introuvable. Contactez le support.');
        }

        return redirect()->to(
            "https://{$user->organization->subdomain}.{$this->getBaseDomain($request)}/dashboard"
        );
    }

    public function logOut(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->away("https://{$this->getBaseDomain($request)}");
    }

    // ─────────────────────────────────────────────
    //  Gestion des abonnements
    // ─────────────────────────────────────────────

    /**
     * Appelée après le paiement de l'INSCRIPTION (callback de paiement).
     * Crée l'organisation + l'utilisateur + démarre l'abonnement.
     */
    public function success(Request $request, string $subdomain)
    {
        try {
            $sessionData = session('registration_data');

            if (!$sessionData || $sessionData['subdomain'] !== $subdomain) {
                Log::warning('success() : session invalide', ['subdomain' => $subdomain]);
                return response()->json(['error' => 'Session expirée ou sous-domaine invalide.'], 400);
            }

            // Création de l'organisation
            $organization = Organization::create([
                'organization_name'    => $sessionData['organization_name'],
                'organization_email'   => $sessionData['organization_email'],
                'organization_address' => $sessionData['organization_address'],
                'organization_phone'   => $sessionData['organization_phone'],
                'organization_logo'    => $sessionData['organization_logo'],
                'subdomain'            => $sessionData['subdomain'],
            ]);

            // Création de l'utilisateur avec abonnement 1 mois
            // ✅ subscription_started_at stocke QUAND l'abonnement a démarré,
            //    indépendamment de updated_at qui sera modifié par n'importe quel save()
            $user = User::create([
                'name'                    => $sessionData['name'],
                'email'                   => $sessionData['email'],
                'password'                => Hash::make($sessionData['password']),
                'phone'                   => $sessionData['phone'],
                'address'                 => $sessionData['address'],
                'organization_id'         => $organization->id,
                'subscription_quantity'   => 1,
                'subscription_started_at' => now(),  // ✅ colonne dédiée, jamais écrasée accidentellement
            ]);

            UserOrganization::create([
                'user_id'         => $user->id,
                'organization_id' => $organization->id,
            ]);

            session()->forget('registration_data');
            Auth::login($user);

            Log::info('Inscription OK', [
                'user_id'    => $user->id,
                'org_id'     => $organization->id,
                'expiry'     => $user->subscription_expiry->toDateTimeString(),
            ]);

            return redirect()->to(
                "https://{$subdomain}.{$this->getBaseDomain($request)}/dashboard"
            );
        } catch (\Exception $e) {
            Log::error('Erreur success()', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Erreur lors de la création du compte.'], 500);
        }
    }

    /**
     * Appelée après le paiement d'un RENOUVELLEMENT (callback Kkiapay).
     *
     * ⚠️  Ce callback arrive depuis e-benin.com (domaine principal) ALORS QUE
     *     l'utilisateur était connecté sur subdomain.e-benin.com.
     *     La session est donc PERDUE → on ne peut PAS utiliser Auth::user().
     *
     *     Solution : le subdomain est passé dans l'URL du callback
     *     (?quantite=X&subdomain=monblog) et on retrouve l'utilisateur via
     *     son organisation.
     *
     * URL attendue : /update-subscription?quantite=2&subdomain=monblog
     */
    public function updateSubscription(Request $request)
    {
        Log::info('updateSubscription() appelée', $request->all());

        // ── 1. Récupérer le subdomain depuis le callback URL ──────────────
        $subdomain = $request->query('subdomain');

        if (!$subdomain) {
            // Fallback : si Auth fonctionne quand même (même domaine)
            $subdomain = Auth::user()?->organization?->subdomain;
        }

        if (!$subdomain) {
            Log::error('updateSubscription() : subdomain manquant dans le callback');
            return redirect()->away("https://e-benin.com")
                ->with('error', 'Impossible d\'identifier votre compte. Contactez le support.');
        }

        // ── 2. Trouver l'organisation et l'utilisateur ────────────────────
        $organization = Organization::where('subdomain', $subdomain)->first();

        if (!$organization) {
            Log::error('updateSubscription() : organisation introuvable', ['subdomain' => $subdomain]);
            return redirect()->away("https://e-benin.com")
                ->with('error', 'Organisation introuvable. Contactez le support.');
        }

        // On prend le premier user de l'organisation (le propriétaire du blog)
        $user = User::where('organization_id', $organization->id)->first();

        if (!$user) {
            Log::error('updateSubscription() : utilisateur introuvable', ['org_id' => $organization->id]);
            return redirect()->away("https://e-benin.com")
                ->with('error', 'Utilisateur introuvable. Contactez le support.');
        }

        // ── 3. Mettre à jour l'abonnement ────────────────────────────────
        $quantity = max(1, (int) $request->query('quantite', 1));

        // Point de départ :
        // - abonnement encore actif → on part de l'expiration actuelle (pas de perte de jours)
        // - abonnement expiré ou inexistant → on repart de maintenant
        $currentExpiry = ($user->subscription_started_at && $user->subscription_quantity)
            ? $user->subscription_started_at->copy()->addMonths($user->subscription_quantity)
            : null;

        $newStart = ($currentExpiry && now()->lessThan($currentExpiry)) ? $currentExpiry : now();

        $user->subscription_started_at = $newStart;
        $user->subscription_quantity   = ($user->subscription_quantity ?? 0) + $quantity;
        $user->save();

        Log::info('Abonnement renouvelé avec succès', [
            'user_id'               => $user->id,
            'subdomain'             => $subdomain,
            'quantity_added'        => $quantity,
            'subscription_quantity' => $user->subscription_quantity,
            'new_start'             => $newStart->toDateTimeString(),
            'new_expiry'            => $newStart->copy()->addMonths($user->subscription_quantity)->toDateTimeString(),
        ]);

        // ── 4. Reconnecter l'utilisateur et rediriger vers son dashboard ──
        Auth::login($user);

        $baseDomain  = str_contains($request->getHost(), 'e-benin.bj') ? 'e-benin.bj' : 'e-benin.com';
        $redirectUrl = "https://{$subdomain}.{$baseDomain}/dashboard";

        return redirect()->to($redirectUrl)
            ->with('success', "Abonnement prolongé de {$quantity} mois avec succès !");
    }

    /**
     * Appelée si l'utilisateur est déjà connecté sur le subdomain mais bloqué
     * sur la page /subscription. On vérifie juste que l'abonnement est actif.
     */
    public function loginBySubscription(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->away('https://e-benin.com/bloger/login')
                ->with('error', 'Vous devez etre connecte.');
        }

        $user = $user->fresh();

        $expiry = ($user->subscription_started_at && $user->subscription_quantity)
            ? $user->subscription_started_at->copy()->addMonths($user->subscription_quantity)
            : null;

        $isActive = $expiry && now()->lessThanOrEqualTo($expiry);

        if (!$isActive) {
            Log::warning('loginBySubscription() : abonnement toujours inactif', [
                'user_id' => $user->id,
                'expiry'  => $expiry?->toDateTimeString() ?? 'null',
            ]);
            $baseDomain = $this->getBaseDomain($request);
            return redirect()->to("https://{$user->organization->subdomain}.{$baseDomain}/subscription")
                ->with('error', 'Paiement non encore valide. Attendez quelques instants ou contactez le support.');
        }

        $baseDomain = $this->getBaseDomain($request);
        return redirect()->to("https://{$user->organization->subdomain}.{$baseDomain}/dashboard");
    }

    // ─────────────────────────────────────────────
    //  Utilitaires privés
    // ─────────────────────────────────────────────

    private function paginatePosts($posts, string $groupBy = 'user', int $perPage = 4)
    {
        $keyFn = $groupBy === 'organization'
            ? fn($p) => $p->user->organization->id
            : fn($p) => $p->user->id;

        $flat = $posts->groupBy($keyFn)->map(fn($g) => $g->shuffle())->flatten();
        $page = request()->input('page', 1);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $flat->forPage($page, $perPage),
            $flat->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }
}
