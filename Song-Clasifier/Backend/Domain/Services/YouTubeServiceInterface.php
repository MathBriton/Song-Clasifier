<?php
// backend/app/Domain/Services/YouTubeServiceInterface.php

declare(strict_types=1);

namespace App\Domain\Services;

interface YouTubeServiceInterface
{
    /**
     * Obter dados de um vídeo do YouTube
     */
    public function obterDadosVideo(string $videoId): array;

    /**
     * Verificar se um vídeo existe
     */
    public function verificarVideoExiste(string $videoId): bool;

    /**
     * Obter URL da thumbnail do vídeo
     */
    public function obterThumbnailUrl(string $videoId, string $quality = 'maxresdefault'): string;
}
