<?php
// backend/app/Domain/UseCases/ListarTop5MusicasUseCase.php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Repositories\MusicaRepositoryInterface;

final class ListarTop5MusicasUseCase
{
    public function __construct(
        private readonly MusicaRepositoryInterface $musicaRepository
    ) {}

    public function execute(): array
    {
        $musicas = $this->musicaRepository->getTop5();

        return [
            'success' => true,
            'data' => array_map(fn($musica) => $musica->toArray(), $musicas),
            'total' => count($musicas),
            'message' => 'Top 5 músicas listadas com sucesso'
        ];
    }
}