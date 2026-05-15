<?php

use App\Http\Controllers\Api\ReaderAuthController;
use App\Http\Controllers\Api\ReaderAppController;
use Illuminate\Support\Facades\Route;

// ── Auth (public) ──────────────────────────────────────────────────────────
Route::prefix('reader')->group(function () {
    Route::post('/register', [ReaderAuthController::class, 'register']);
    Route::post('/login',    [ReaderAuthController::class, 'login']);
});

// ── App (protégé par token Sanctum) ───────────────────────────────────────
Route::prefix('reader')->middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [ReaderAuthController::class, 'logout']);
    Route::get('/me',      [ReaderAuthController::class, 'me']);

    // Articles
    Route::get('/articles',                       [ReaderAppController::class, 'articles']);
    Route::get('/articles/{id}',                  [ReaderAppController::class, 'article']);
    Route::post('/articles/{id}/favorite',        [ReaderAppController::class, 'toggleFavorite']);
    Route::post('/articles/{id}/comment',         [ReaderAppController::class, 'addComment']);

    // Catégories
    Route::get('/categories',                     [ReaderAppController::class, 'categories']);

    // Favoris
    Route::get('/favoris',                        [ReaderAppController::class, 'favoris']);

    // Annonces
    Route::get('/annonces',                       [ReaderAppController::class, 'annonces']);
    Route::get('/annonces/{id}',                  [ReaderAppController::class, 'annonceShow']);

    // Nécrologies
    Route::get('/necrologies',                    [ReaderAppController::class, 'necrologies']);
    Route::get('/necrologies/{id}',               [ReaderAppController::class, 'necrologieShow']);
});
