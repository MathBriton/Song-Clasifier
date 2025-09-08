<?php

namespace App\UseCases;

use App\Models\Musica;
use App\Models\Repositories\MusicaRepositoryInterface;
use App\Models\ValueObjects\YouTubeUrl;
use App\Models\Exceptions\MusicaJaExisteException;
use App\Models\Services\YouTubeServiceInterface;

final class SugerirMusicaUseCase
{
    public function __construct(
        private readonly MusicaRepositoryInterface $musicaRepository,
        private readonly YouTubeServiceInterface $youTubeService
    ) {}

    public function execute(string $youtubeUrl, ?int $userId = null): array
    {
        // Validar e criar URL do YouTube
        $youTubeUrlVO = YouTubeUrl::fromString($youtubeUrl);

        // Verificar se a música já existe
        if ($this->musicaRepository->existsByYouTubeUrl($youTubeUrlVO)) {
            throw new MusicaJaExisteException('Esta música já foi sugerida anteriormente');
        }

        // Buscar dados da música no YouTube
        $dadosYouTube = $this->youTubeService->obterDadosVideo($youTubeUrlVO->getId());

        // Validar se é uma música do Tião Carreiro
        if (!$this->isValidTiaoCarreiroSong($dadosYouTube['title'], $dadosYouTube['channel'])) {
            throw new \InvalidArgumentException(
                'Esta música não parece ser do Tião Carreiro & Pardinho. ' .
                'Verifique se o título contém o nome dos artistas.'
            );
        }

        // Criar entidade da música
        $musica = Musica::create(
            titulo: $dadosYouTube['title'],
            artista: 'Tião Carreiro & Pardinho',
            youtubeUrl: $youTubeUrlVO,
            visualizacoes: $dadosYouTube['view_count'],
            thumbnailUrl: $dadosYouTube['thumbnail_url'],
            duracao: $dadosYouTube['duration'],
            userId: $userId
        );

        // Salvar no repositório
        $musicaSalva = $this->musicaRepository->create($musica);

        return [
            'success' => true,
            'data' => $musicaSalva->toArray(),
            'message' => 'Música sugerida com sucesso! Aguarde a aprovação do administrador.'
        ];
    }

    private function isValidTiaoCarreiroSong(string $title, string $channel): bool
    {
        $keywords = [
            'tião carreiro',
            'tiao carreiro',
            'pardinho',
            'carreiro',
            'dupla caipira'
        ];

        $titleLower = mb_strtolower($title);
        $channelLower = mb_strtolower($channel);

        foreach ($keywords as $keyword) {
            if (str_contains($titleLower, $keyword) || str_contains($channelLower, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
