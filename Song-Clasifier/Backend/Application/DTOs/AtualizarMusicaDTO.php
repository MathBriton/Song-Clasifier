<?php

namespace App\Application\DTOs;

final readonly class AtualizarMusicaDTO
{
    public function __construct(
        public int $id,
        public string $titulo,
        public string $artista,
        public string $youtubeUrl,
        public int $visualizacoes,
        public string $thumbnailUrl,
        public int $duracao
    ) {}

    public static function fromArray(array $data, int $id): self
    {
        return new self(
            id: $id,
            titulo: $data['titulo'] ?? '',
            artista: $data['artista'] ?? 'Tião Carreiro & Pardinho',
            youtubeUrl: $data['youtube_url'] ?? '',
            visualizacoes: (int) ($data['visualizacoes'] ?? 0),
            thumbnailUrl: $data['thumbnail_url'] ?? '',
            duracao: (int) ($data['duracao'] ?? 0)
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'artista' => $this->artista,
            'youtube_url' => $this->youtubeUrl,
            'visualizacoes' => $this->visualizacoes,
            'thumbnail_url' => $this->thumbnailUrl,
            'duracao' => $this->duracao,
        ];
    }
}
