<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MusicaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MusicaController extends Controller
{
    public function __construct(
        private MusicaService $musicaService
    ) {}

    /**
     * Listar músicas com paginação e filtros
     * GET /api/musicas
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'page' => 'integer|min:1',
                'per_page' => 'integer|min:1|max:100',
                'status' => 'string|in:pendente,aprovada,reprovada',
                'search' => 'string|max:255',
                'order_by' => 'string|in:titulo,artista,visualizacoes,created_at',
                'order_direction' => 'string|in:asc,desc'
            ]);

            $result = $this->musicaService->listarMusicasPaginadas(
                page: $validated['page'] ?? 1,
                perPage: $validated['per_page'] ?? 15,
                status: $validated['status'] ?? null,
                search: $validated['search'] ?? null,
                orderBy: $validated['order_by'] ?? 'visualizacoes',
                orderDirection: $validated['order_direction'] ?? 'desc'
            );

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'pagination' => $result['pagination']
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao listar músicas', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Criar nova música
     * POST /api/musicas
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'artista' => 'required|string|max:255',
                'youtube_url' => 'required|url',
                'visualizacoes' => 'integer|min:0',
                'thumbnail_url' => 'url|max:500',
                'duracao' => 'integer|min:0',
                'user_id' => 'integer|exists:users,id'
            ]);

            $musica = $this->musicaService->criarMusica($validated);

            return response()->json([
                'success' => true,
                'message' => 'Música criada com sucesso',
                'data' => $musica->toArray()
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao criar música', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            if (str_contains($e->getMessage(), 'já existe')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 409);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Exibir música específica
     * GET /api/musicas/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $musica = $this->musicaService->findById($id);

            return response()->json([
                'success' => true,
                'data' => $musica->toArray()
            ]);

        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'não encontrada')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Música não encontrada'
                ], 404);
            }

            Log::error('Erro ao buscar música', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Atualizar música
     * PUT /api/musicas/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'titulo' => 'string|max:255',
                'artista' => 'string|max:255',
                'youtube_url' => 'url',
                'visualizacoes' => 'integer|min:0',
                'thumbnail_url' => 'url|max:500',
                'duracao' => 'integer|min:0'
            ]);

            $musica = $this->musicaService->atualizarMusica($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Música atualizada com sucesso',
                'data' => $musica->toArray()
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'não encontrada')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Música não encontrada'
                ], 404);
            }

            if (str_contains($e->getMessage(), 'já existe')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 409);
            }

            Log::error('Erro ao atualizar música', [
                'id' => $id,
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Excluir música
     * DELETE /api/musicas/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->musicaService->excluirMusica($id);

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Música excluída com sucesso'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível excluir a música'
            ], 500);

        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'não encontrada')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Música não encontrada'
                ], 404);
            }

            Log::error('Erro ao excluir música', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Top 5 músicas mais tocadas
     * GET /api/musicas/top5
     */
    public function top5(): JsonResponse
    {
        try {
            $musicas = $this->musicaService->getTop5();

            return response()->json([
                'success' => true,
                'data' => $musicas
            ]);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar top 5 músicas', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Músicas mais recentes
     * GET /api/musicas/recentes
     */
    public function recentes(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'limit' => 'integer|min:1|max:50'
            ]);

            $musicas = $this->musicaService->getMusicasRecentes(
                $validated['limit'] ?? 10
            );

            return response()->json([
                'success' => true,
                'data' => $musicas
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar músicas recentes', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Músicas mais populares
     * GET /api/musicas/populares
     */
    public function populares(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'limit' => 'integer|min:1|max:50'
            ]);

            $musicas = $this->musicaService->getMusicasPopulares(
                $validated['limit'] ?? 10
            );

            return response()->json([
                'success' => true,
                'data' => $musicas
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar músicas populares', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Buscar músicas por artista
     * GET /api/musicas/artista/{artista}
     */
    public function porArtista(Request $request, string $artista): JsonResponse
    {
        try {
            $validated = $request->validate([
                'limit' => 'integer|min:1|max:50'
            ]);

            $musicas = $this->musicaService->buscarPorArtista(
                $artista,
                $validated['limit'] ?? 10
            );

            return response()->json([
                'success' => true,
                'data' => $musicas
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar músicas por artista', [
                'artista' => $artista,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Incrementar visualização
     * POST /api/musicas/{id}/view
     */
    public function incrementarView(int $id): JsonResponse
    {
        try {
            $success = $this->musicaService->incrementarVisualizacoes($id);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Visualização incrementada'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível incrementar visualização'
            ], 500);

        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'não encontrada')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Música não encontrada'
                ], 404);
            }

            Log::error('Erro ao incrementar visualização', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Buscar por URL do YouTube
     * GET /api/musicas/youtube?url={url}
     */
    public function buscarPorYouTubeUrl(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'url' => 'required|url'
            ]);

            $musica = $this->musicaService->buscarPorYouTubeUrl($validated['url']);

            if ($musica) {
                return response()->json([
                    'success' => true,
                    'data' => $musica->toArray()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Música não encontrada'
            ], 404);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar música por URL', [
                'url' => $request->get('url'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
}