<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\DTOs;
use App\Models\UseCases\ListarTop5MusicasUseCase;

class MusicaController extends Controller

{
     public function index(Request $request): JsonResponse
     {
         try {
            $filtro = FiltroMusicaDTO::fromArray($request->all());
            $result = $this->listarMusicasUseCase->execute($filtro);

            return response()->json([
                'success' => true,
                'data' => 'data',
                'pagination' =>'pagination',
                'message' => 'message'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar músicas: ' . $e->getMessage()
            ], 500);
        }
    }

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

    public function sugerir(Request $request): JsonResponse
    {
        try {
            $dto = SugerirMusicaDTO::fromArray($request->validated());
            $result = $this->sugerirMusicaUseCase->execute(
                $dto->youtubeUrl,
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
    // $data = [
    //             'status' => 'success',
    //             'message' => 'This is fake data from the API.',
    //             'user' => [
    //                 'id' => 1,
    //                 'name' => 'John Doe',
    //                 'email' => 'john.doe@example.com'
    //             ]
    //         ];

    //         return response()->json($data, 200);
    //     }

