<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Models\biographie;
use App\Models\Organization;
use App\Models\organization_social;
use App\Models\social;
use App\Models\post;
use App\Models\comment;
use App\Models\publicite;
use App\Models\rubrique;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\articleController;
use App\Http\Controllers\authController;
use App\Http\Controllers\bioController;
use App\Http\Controllers\pubController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ═══════════════════════════════════════════════════════════════════════
//  Routes communes (pas de contrainte de domaine)
// ═══════════════════════════════════════════════════════════════════════
Route::post('logout', [HomeController::class, 'logOut'])->name('logOut');
Route::post('comment/{post}', [PostController::class, 'storeComment'])->name('comments.store');


// ═══════════════════════════════════════════════════════════════════════
//  Helper : routes identiques sur e-benin.com ET e-benin.bj
//  On déclare les routes pour chaque domaine principal explicitement.
//  ⚠️  NE PAS mettre update-subscription en route globale : avec des
//      Route::domain() configurés, Laravel ne matche pas les routes
//      globales pour ces domaines-là.
// ═══════════════════════════════════════════════════════════════════════

$mainDomainRoutes = function () {
    // Accueil
    Route::get('/', [HomeController::class, 'navbar']);

    // ✅ Callback Kkiapay renouvellement — DOIT être ici, pas en global
    //    Kkiapay redirige vers e-benin.com/update-subscription ou e-benin.bj/update-subscription
    //    On accepte GET et POST pour couvrir tous les cas (certains SDK font POST)
    Route::match(['get', 'post'], '/update-subscription', [HomeController::class, 'updateSubscription'])
        ->name('update-subscription');

    // Callback Kkiapay inscription
    Route::get('transaction/{subdomain}', [HomeController::class, 'success'])
        ->name('transaction.success');

    // Pages statiques
    Route::get('/subscription', fn() => view('subscription'))->name('subscription');
    Route::get('/politique',    fn() => view('politique'))->name('politique');
    Route::get('/categories/{id}', [HomeController::class, 'allCategories'])->name('categories');

    // Auth
    Route::get('/forgot-password', [authController::class, 'ForgotPasswordForm'])->name('forgotView');

    // Inscription blogueur
    Route::prefix('bloger')->group(function () {
        Route::post('/login',          [HomeController::class,  'userLogin'])->name('userLogin');
        Route::get('register',         [HomeController::class,  'userRegisterView'])->name('userRegister');
        Route::post('register',        [RegisterController::class, 'register'])->name('register');
        Route::post('/forgot-password', [authController::class,  'forgotPassword'])->name('password.forgot');
    });

    // Routes auth protégées
    Route::middleware(['auth'])->group(function () {
        Route::post('/articles/store',      [articleController::class, 'store'])->name('articles.store');
        Route::put('/articles/update/{id}', [articleController::class, 'update'])->name('articles.update');
        Route::delete('/articles/{id}',     [articleController::class, 'destroy'])->name('articles.delete');

        Route::post('bio/create',           [bioController::class, 'store'])->name('bio.store');
        Route::put('bio/update/{id}',       [bioController::class, 'update'])->name('bio.update');
        Route::put('org/update{id}',        [bioController::class, 'updateOrg'])->name('org.update');

        Route::post('social/store',         [bioController::class, 'storeSocial'])->name('social.store');
        Route::put('social/update{id}',     [bioController::class, 'updateSocial'])->name('social.update');

        Route::post('/update-password',     [authController::class, 'updatePassword'])->name('password.update');
        Route::post('/upload-image',        [HomeController::class, 'upload'])->name('upload.image');

        // Publicités
        Route::post('/publicite',           [pubController::class, 'create'])->name('publicite.create');
        Route::put('/publicites/{id}',      [pubController::class, 'update'])->name('publicite.update');
        Route::delete('/publicites/{id}',   [pubController::class, 'delete'])->name('publicite.delete');
    });
};

// Enregistrement pour les deux domaines principaux
Route::domain('e-benin.com')->group($mainDomainRoutes);
Route::domain('e-benin.bj')->group($mainDomainRoutes);


// ═══════════════════════════════════════════════════════════════════════
//  Routes sous-domaines : {blog}.e-benin.com et {blog}.e-benin.bj
// ═══════════════════════════════════════════════════════════════════════

$subdomainRoutes = function ($domain) {
    Route::domain('{organization}.' . $domain)->group(function () {

        // Pages publiques
        Route::get('/blog',          [HomeController::class, 'index'])->name('home');
        Route::get('post/{id}',      [PostController::class, 'show'])->name('single-post');
        Route::get('/category/{id}', [HomeController::class, 'showUserRubrique'])->name('category.show');

        // Page abonnement (accessible sans auth sur le sous-domaine aussi)
        Route::get('/subscription', fn() => view('subscription'))->name('subscription');

        // Dashboard protégé
        Route::middleware(['auth'])->group(function () {
            // Actions dashboard sur sous-domaine (évite les 419 CSRF entre hôtes)
            Route::post('/articles/store',      [articleController::class, 'store']);
            Route::put('/articles/update/{id}', [articleController::class, 'update']);
            Route::delete('/articles/{id}',     [articleController::class, 'destroy']);

            Route::post('bio/create',           [bioController::class, 'store']);
            Route::put('bio/update/{id}',       [bioController::class, 'update']);
            Route::put('org/update{id}',        [bioController::class, 'updateOrg']);

            Route::post('social/store',         [bioController::class, 'storeSocial']);
            Route::put('social/update{id}',     [bioController::class, 'updateSocial']);

            Route::post('/update-password',     [authController::class, 'updatePassword']);
            Route::post('/upload-image',        [HomeController::class, 'upload']);

            Route::post('/publicite',           [pubController::class, 'create']);
            Route::put('/publicites/{id}',      [pubController::class, 'update']);
            Route::delete('/publicites/{id}',   [pubController::class, 'delete']);

            Route::get('/dashboard', function (Request $request) {
                $user         = Auth::user();
                $biographie   = biographie::where('user_id', $user->id)->first();
                $organization = $user->organization;

                // Vérification abonnement avec subscription_started_at
                $expiryDate = ($user->subscription_started_at && $user->subscription_quantity)
                    ? $user->subscription_started_at->copy()->addMonths($user->subscription_quantity)
                    : null;

                if (!$expiryDate || now()->greaterThanOrEqualTo($expiryDate)) {
                    return redirect('//' . request()->getHost() . '/subscription')
                        ->with('error', 'Votre abonnement a expire. Veuillez le renouveler.');
                }

                $posts = Post::where('user_id', $user->id)
                    ->with('user', 'rubriques', 'comments')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                if ($request->ajax()) {
                    return view('partials.posts', compact('posts'))->render();
                }

                $rubriques  = Rubrique::all();
                $orgSocials = organization_social::where('organization_id', $organization->id)->get();
                $reseaux    = social::all();
                $comments   = Comment::whereIn('post_id', $posts->pluck('id'))->get();
                $publicites = publicite::all();

                return view('myBlog.board', compact(
                    'user',
                    'publicites',
                    'orgSocials',
                    'reseaux',
                    'biographie',
                    'organization',
                    'rubriques',
                    'posts',
                    'comments'
                ));
            })->name('dashboard');
        });

        // Racine → /blog
        Route::get('/', function () {
            return redirect('//' . request()->getHost() . '/blog');
        });
    });
};

$subdomainRoutes('e-benin.com');
$subdomainRoutes('e-benin.bj');
