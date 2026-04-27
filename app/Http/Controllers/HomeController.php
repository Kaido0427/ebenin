<?php

namespace App\Http\Controllers;

use App\Models\organization;
use App\Models\OrganizationSubscription;
use App\Models\post;
use App\Models\rubrique;
use App\Models\transaction as Transaction;
use App\Models\User;
use App\Models\publicite;
use App\Models\userOrganization;
use App\Models\organization_social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $this->abortIfOrganizationUnavailable($organization);

        $user = Auth::user();

        $rubriques = Rubrique::whereHas('posts', function ($q) use ($organization) {
            $q->published()->whereHas('user', fn($q2) => $q2->where('organization_id', $organization->id));
        })->get();

        // Un post aléatoire (sans vidéo) par rubrique
        $randomPosts = $rubriques->map(function ($rubrique) use ($organization) {
            $posts = $rubrique->posts->filter(
                fn($p) => $p->user->organization_id === $organization->id
                    && is_null($p->video)
                    && ($p->editorial_status ?? 'published') === 'published'
            );
            return $posts->isNotEmpty()
                ? ['rubrique' => $rubrique, 'post' => $posts->random()]
                : null;
        })->filter()->values();

        // Dernière actualité (sans vidéo)
        $latestNews = $rubriques->flatMap(function ($rubrique) use ($organization) {
            return $rubrique->posts->filter(
                fn($p) => $p->user->organization_id === $organization->id
                    && is_null($p->video)
                    && ($p->editorial_status ?? 'published') === 'published'
            );
        })->sortByDesc('created_at')->first();

        // Posts à la une
        $featuredPosts = Post::where('featured', 1)
            ->published()
            ->whereHas('user', fn($q) => $q->where('organization_id', $organization->id))
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        // Reportages (vidéos ou rubrique "reportage")
        $reportages = Post::whereIn('user_id', $organization->users->pluck('id'))
            ->published()
            ->where(
                fn($q) => $q
                    ->whereNotNull('video')
                    ->orWhereHas('rubriques', fn($q2) => $q2->where('name', 'reportage'))
            )
            ->with('user', 'rubriques', 'comments')
            ->orderByDesc('created_at')
            ->get();

        $randomTags = Rubrique::inRandomOrder()->take(10)->get();
        $socials     = organization_social::where('organization_id', $organization->id)->get();
        $pub         = Publicite::where('space', 'blog.e-benin')->first();
        $breakingPosts = Post::published()
            ->where('is_breaking', true)
            ->whereHas('user', fn($q) => $q->where('organization_id', $organization->id))
            ->latest()
            ->take(8)
            ->get();

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
            'reportages',
            'breakingPosts'
        ));
    }

    public function navbar()
    {
        $rubriques             = Rubrique::whereHas('posts', fn($q) => $q->published())->with('posts')->get();
        $latestPosts           = Post::published()->whereNull('video')->orderByDesc('created_at')->take(15)->get();
        $flashNews             = Post::published()->where('is_breaking', 1)->orderByDesc('created_at')->take(6)->get();
        $newPosts              = Post::published()->whereNull('video')->orderByDesc('created_at')->take(4)->get();
        $featuredPosts         = Post::published()->where('featured', 1)->orderByDesc('created_at')->get();
        $rubriquesWithoutPosts = Rubrique::all();
        $reportages            = Post::published()->whereNotNull('video')->with('user', 'rubriques', 'comments')->get();
        $tags                  = Rubrique::all();
        $pub                   = Publicite::where('space', 'e-benin')->first();
        $footerOrgs            = Organization::where('is_active', true)
            ->where('is_publicly_visible', true)
            ->whereHas('users.posts', fn($q) => $q->published())
            ->get();

        if ($flashNews->isEmpty()) {
            $flashNews = $latestPosts->take(6);
        }

        $randomizedPosts = [];
        foreach (Organization::where('is_active', true)->where('is_publicly_visible', true)->with(['users.posts'])->get() as $org) {
            $subdomain = urlencode($org->subdomain);
            foreach ($org->users as $u) {
                foreach ($u->posts as $post) {
                    if (($post->editorial_status ?? 'published') !== 'published') {
                        continue;
                    }
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
        $this->abortIfOrganizationUnavailable($organization);
        $rubrique     = Rubrique::with('posts')->findOrFail($id);

        $posts          = $rubrique->posts->filter(fn($p) => $p->user->organization->id === $organization->id && ($p->editorial_status ?? 'published') === 'published');
        $paginatedPosts = $this->paginatePosts($posts, 'organization');
        $rubriquesGuest = Rubrique::whereHas('posts', fn($q) => $q->published())->get();

        return view('myBlog.category', compact('rubrique', 'paginatedPosts', 'rubriquesGuest', 'organization'));
    }

    public function allCategories(int $id)
    {
        $rubrique       = Rubrique::with('posts')->findOrFail($id);
        $paginatedPosts = $this->paginatePosts($rubrique->posts->where('editorial_status', 'published'), 'user');
        $rubriquesGuest = Rubrique::whereHas('posts', fn($q) => $q->published())->get();

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

        if (!$user->is_active || !$user->organization?->is_active) {
            return back()->withErrors(['email' => 'Ce compte est suspendu. Contactez l\'administration.'])->withInput();
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
                'is_active'            => true,
                'is_publicly_visible'  => true,
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
                'is_active'               => true,
            ]);

            $subscription = $this->syncOrganizationSubscription($organization, 1, 'active');
            $this->syncLegacySubscriptionFields($organization, $subscription);

            Transaction::create([
                'phone' => $organization->organization_phone ?? $user->phone ?? 'N/A',
                'amount' => 10000,
                'status' => 'paid',
                'token' => (string) Str::uuid(),
                'payment_method' => 'kkiapay',
                'organization_id' => $organization->id,
                'source' => 'kkiapay',
                'reference' => $request->query('transaction_id') ?: 'registration-' . $organization->subdomain,
                'paid_at' => now(),
                'months_awarded' => 1,
                'notes' => 'Activation initiale du blog',
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

        $subscription = $this->syncOrganizationSubscription($organization, $quantity, 'active');
        $this->syncLegacySubscriptionFields($organization, $subscription);

        Transaction::create([
            'phone' => $organization->organization_phone ?? $user->phone ?? 'N/A',
            'amount' => 10000 * $quantity,
            'status' => 'paid',
            'token' => (string) Str::uuid(),
            'payment_method' => 'kkiapay',
            'organization_id' => $organization->id,
            'source' => 'kkiapay',
            'reference' => $request->query('transaction_id') ?: 'renewal-' . $organization->subdomain . '-' . now()->timestamp,
            'paid_at' => now(),
            'months_awarded' => $quantity,
            'notes' => 'Renouvellement via callback Kkiapay',
        ]);

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

    private function syncOrganizationSubscription(Organization $organization, int $monthsToAdd, string $status = 'active'): OrganizationSubscription
    {
        $subscription = OrganizationSubscription::firstOrNew([
            'organization_id' => $organization->id,
        ]);

        $currentExpiry = $subscription->expires_at;
        $baseStart = ($currentExpiry && now()->lessThan($currentExpiry)) ? $currentExpiry->copy() : now();

        if (!$subscription->exists || !$subscription->started_at) {
            $subscription->started_at = now();
        }

        $subscription->plan_name = $subscription->plan_name ?: 'Blog Standard';
        $subscription->status = $status;
        $subscription->renewal_cycle_months = 1;
        $subscription->is_auto_renew = false;
        $subscription->last_payment_at = now();
        $subscription->expires_at = $baseStart->copy()->addMonths($monthsToAdd);
        $subscription->next_renewal_at = $subscription->expires_at;
        $subscription->save();

        return $subscription->fresh();
    }

    private function syncLegacySubscriptionFields(Organization $organization, OrganizationSubscription $subscription): void
    {
        $owners = User::where('organization_id', $organization->id)->get();

        foreach ($owners as $owner) {
            $owner->subscription_started_at = $subscription->started_at;
            $owner->subscription_quantity = $subscription->started_at && $subscription->expires_at
                ? max(1, $subscription->started_at->diffInMonths($subscription->expires_at))
                : 0;
            $owner->save();
        }
    }

    private function abortIfOrganizationUnavailable(Organization $organization): void
    {
        if (!$organization->is_active || !$organization->is_publicly_visible) {
            abort(404);
        }
    }

    // ─────────────────────────────────────────────
    //  Recherche
    // ─────────────────────────────────────────────

    public function search(Request $request)
    {
        $subdomain = $this->getSubdomain();

        if ($subdomain) {
            // Recherche sur un blog spécifique
            $organization = Organization::where('subdomain', $subdomain)->firstOrFail();
            $this->abortIfOrganizationUnavailable($organization);

            $query = $request->input('q', '');
            $rubriqueId = $request->input('rubrique');

            $posts = Post::published()
                ->whereHas('user', fn($q) => $q->where('organization_id', $organization->id))
                ->where(function ($q) use ($query) {
                    $q->where('libelle', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->when($rubriqueId, function ($q) use ($rubriqueId) {
                    $q->whereHas('rubriques', fn($q2) => $q2->where('id', $rubriqueId));
                })
                ->with('user', 'rubriques')
                ->orderByDesc('created_at')
                ->paginate(12);

            $rubriques = Rubrique::whereHas('posts', function ($q) use ($organization) {
                $q->published()->whereHas('user', fn($q2) => $q2->where('organization_id', $organization->id));
            })->get();

            return view('public.search', compact('posts', 'query', 'rubriques', 'organization'));
        } else {
            // Recherche globale sur tous les blogs
            $query = $request->input('q', '');
            $organizationId = $request->input('blog');

            $posts = Post::published()
                ->where(function ($q) use ($query) {
                    $q->where('libelle', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->when($organizationId, function ($q) use ($organizationId) {
                    $q->whereHas('user', fn($q2) => $q2->where('organization_id', $organizationId));
                })
                ->with('user.organization', 'rubriques')
                ->orderByDesc('created_at')
                ->paginate(12);

            $organizations = Organization::where('is_active', true)
                ->where('is_publicly_visible', true)
                ->orderBy('organization_name')
                ->get();

            return view('public.search-global', compact('posts', 'query', 'organizations'));
        }
    }
}
