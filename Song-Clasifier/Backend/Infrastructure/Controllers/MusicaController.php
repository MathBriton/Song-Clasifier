<?php
// backend/app/Infrastructure/Http/Controllers/Api/MusicaController.php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers\Api;

use App\Infrastructure\Http\Controllers\Controller;
use App\Domain\UseCases\ListarTop5MusicasUseCase;
use App\Domain\UseCases\SugerirMusicaUseCase;
use App\Domain\UseCases\ListarMusicasUseCase;
use App\Application\DTOs\SugerirMusicaDTO;
use App\Application\DTOs\FiltroMusicaDTO;
use App\Infrastructure\Http\Requests\SugerirMusicaRequest;
use App\Infrastructure\Http\Resources\MusicaResource;
use App\Infrastructure\Http\Resources\MusicaCollection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class MusicaController extends Controller
{
    public function __construct(
        private readonly ListarTop5MusicasUseCase $listarTop5UseCase,
        private readonly SugerirMusicaUseCase $sugerirMusicaUseCase,
        private readonly ListarMusicasUseCase $listarMusicasUseCase
    ) {}

    /**
     * @OA\Get(
     *     path="/api/musicas/top5",
     *     summary="Listar top 5 músicas mais tocadas",
     *     tags={"Músicas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista das 5 músicas mais tocadas",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Musica")),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function top5(): JsonResponse
    {
        try {
            $result = $this->listarTop5UseCase->execute();

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'total' => $result['total'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar top 5 músicas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/musicas",
     *     summary="Listar músicas com paginação",
     *     tags={"Músicas"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Página atual",
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Itens por página",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Buscar por título ou artista",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de músicas",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filtro = FiltroMusicaDTO::fromArray($request->all());
            $result = $this->listarMusicasUseCase->execute($filtro);

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'pagination' => $result['pagination'],
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar músicas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/musicas/sugerir",
     *     summary="Sugerir nova música",
     *     tags={"Músicas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"youtube_url"},
     *             @OA\Property(property="youtube_url", type="string", example="https://www.youtube.com/watch?v=dQw4w9WgXcQ")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Música sugerida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Musica"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function sugerir(SugerirMusicaRequest $request): JsonResponse
    {
        try {
            $dto = SugerirMusicaDTO::fromArray($request->validated());
            $result = $this->sugerirMusicaUseCase->execute(
                $dto->youtubeUrl,
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'message' => $result['message']
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao sugerir música: ' . $e->getMessage()
            ], 500);
        }
    }
}