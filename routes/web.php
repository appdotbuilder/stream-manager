<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::resource('movies', MovieController::class)->only(['index', 'show', 'store']);
Route::post('/movies/{movie}/comments', [CommentController::class, 'store'])->name('movies.comments.store')->middleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
