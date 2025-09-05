<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Infrastructure\Http\Controllers\Api\AuthController;
use App\Infrastructure\Http\Controllers\Api\MusicaController;
use App\Infrastructure\Http\Controllers\Api\AdminController;

// Rotas públicas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

// Rotas públicas de músicas
Route::prefix('musicas')->group(function () {
    Route::get('/top5', [MusicaController::class, 'top5']);
    Route::get('/', [MusicaController::class, 'index']);
    Route::post('/sugerir', [MusicaController::class, 'sugerir'])->middleware('throttle:5,1');
});

// Rotas protegidas (requer autenticação)
Route::middleware(['auth:api'])->group(function () {
    
    // Autenticação (rotas protegidas)
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Rotas administrativas (requer ser admin)
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        // Gestão de sugestões
        Route::prefix('sugestoes')->group(function () {
            Route::get('/', [AdminController::class, 'listarSugestoes']);
            Route::post('/{id}/aprovar', [AdminController::class, 'aprovarSugestao']);
            Route::post('/{id}/reprovar', [AdminController::class, 'reprovarSugestao']);
            Route::delete('/{id}', [AdminController::class, 'excluirSugestao']);
        });

        // CRUD completo de músicas
        Route::prefix('musicas')->group(function () {
            Route::get('/', [AdminController::class, 'listarMusicas']);
            Route::post('/', [AdminController::class, 'criarMusica']);
            Route::get('/{id}', [AdminController::class, 'visualizarMusica']);
            Route::put('/{id}', [AdminController::class, 'atualizarMusica']);
            Route::delete('/{id}', [AdminController::class, 'excluirMusica']);
            Route::post('/{id}/atualizar-visualizacoes', [AdminController::class, 'atualizarVisualizacoes']);
        });

        // Estatísticas e relatórios
        Route::prefix('estatisticas')->group(function () {
            Route::get('/', [AdminController::class, 'obterEstatisticas']);
            Route::get('/musicas-por-mes', [AdminController::class, 'musicasPorMes']);
            Route::get('/top-sugestores', [AdminController::class, 'topSugestores']);
        });

        // Gestão de usuários
        Route::prefix('usuarios')->group(function () {
            Route::get('/', [AdminController::class, 'listarUsuarios']);
            Route::post('/{id}/ativar', [AdminController::class, 'ativarUsuario']);
            Route::post('/{id}/desativar', [AdminController::class, 'desativarUsuario']);
            Route::delete('/{id}', [AdminController::class, 'excluirUsuario']);
        });
    });
});

// Rotas de healthcheck e informações da API
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '1.0.0'),
        'environment' => config('app.env'),
    ]);
});

Route::get('/info', function () {
    return response()->json([
        'name' => config('app.name'),
        'version' => config('app.version', '1.0.0'),
        'description' => 'API REST para o Top 5 Músicas Tião Carreiro & Pardinho',
        'documentation' => url('/api/documentation'),
        'support' => [
            'email' => 'suporte@tiaocarreiro.com.br',
            'website' => 'https://github.com/seu-usuario/top5-tiao-carreiro-api'
        ],
        'endpoints' => [
            'auth' => [
                'login' => 'POST /api/auth/login',
                'logout' => 'POST /api/auth/logout',
                'refresh' => 'POST /api/auth/refresh',
                'me' => 'GET /api/auth/me',
            ],
            'musicas' => [
                'top5' => 'GET /api/musicas/top5',
                'listar' => 'GET /api/musicas',
                'sugerir' => 'POST /api/musicas/sugerir',
            ],
            'admin' => [
                'sugestoes' => 'GET /api/admin/sugestoes',
                'aprovar' => 'POST /api/admin/sugestoes/{id}/aprovar',
                'reprovar' => 'POST /api/admin/sugestoes/{id}/reprovar',
                'musicas' => 'GET|POST|PUT|DELETE /api/admin/musicas',
                'estatisticas' => 'GET /api/admin/estatisticas',
            ]
        ]
    ]);
});

// Fallback para rotas não encontradas
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint não encontrado',
        'available_endpoints' => url('/api/info')
    ], 404);
});