<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MusicaController;

// Rotas públicas de músicas
Route::prefix('musicas')->group(function () {
    Route::get('/top5', [MusicaController::class, 'top5']);
    Route::get('/', [MusicaController::class, 'index']);
    Route::post('/sugerir', [MusicaController::class, 'sugerir'])->middleware('throttle:5,1');
});

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');



Route::middleware(['auth', 'verified'])->group(function () {
    // Gestão de sugestões
        Route::prefix('sugestoes')->group(function () {
            // Route::get('/', [AdminController::class, 'listarSugestoes']);
            // Route::post('/{id}/aprovar', [AdminController::class, 'aprovarSugestao']);
            // Route::post('/{id}/reprovar', [AdminController::class, 'reprovarSugestao']);
            // Route::delete('/{id}', [AdminController::class, 'excluirSugestao']);
        });

        // CRUD completo de músicas
        Route::prefix('musicas')->group(function () {
            // Route::get('/', [AdminController::class, 'listarMusicas']);
            // Route::post('/', [AdminController::class, 'criarMusica']);
            // Route::get('/{id}', [AdminController::class, 'visualizarMusica']);
            // Route::put('/{id}', [AdminController::class, 'atualizarMusica']);
            // Route::delete('/{id}', [AdminController::class, 'excluirMusica']);
            // Route::post('/{id}/atualizar-visualizacoes', [AdminController::class, 'atualizarVisualizacoes']);
        });

    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
