<?php

use App\Http\Controllers\Api\ReaderAuthController;
use App\Http\Controllers\Api\ReaderAppController;
use Illuminate\Support\Facades\Route;

// ── Public (sans connexion) ────────────────────────────────────────────────
Route::prefix('reader')->group(function () {
    Route::post('/register', [ReaderAuthController::class, 'register']);
    Route::post('/login',    [ReaderAuthController::class, 'login']);

    // Lecture publique
    Route::get('/articles',          [ReaderAppController::class, 'articles']);
    Route::get('/articles/{id}',     [ReaderAppController::class, 'article']);
    Route::get('/categories',        [ReaderAppController::class, 'categories']);
    Route::get('/annonces',          [ReaderAppController::class, 'annonces']);
    Route::get('/annonces/{id}',     [ReaderAppController::class, 'annonceShow']);
    Route::get('/necrologies',       [ReaderAppController::class, 'necrologies']);
    Route::get('/necrologies/{id}',  [ReaderAppController::class, 'necrologieShow']);
});

// ── Protégé (connexion requise) ────────────────────────────────────────────
Route::prefix('reader')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout',                        [ReaderAuthController::class, 'logout']);
    Route::get('/me',                             [ReaderAuthController::class, 'me']);
    Route::post('/articles/{id}/favorite',        [ReaderAppController::class, 'toggleFavorite']);
    Route::post('/articles/{id}/comment',         [ReaderAppController::class, 'addComment']);
    Route::get('/favoris',                        [ReaderAppController::class, 'favoris']);
});
