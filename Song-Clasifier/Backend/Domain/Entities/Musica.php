<?php
// backend/app/Domain/Entities/Musica.php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\YouTubeUrl;
use App\Domain\ValueObjects\MusicaStatus;
use Carbon\Carbon;

final class Musica
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $titulo,
        private readonly string $artista,
        private readonly YouTubeUrl $youtubeUrl,
        private readonly int $visualizacoes,
        private readonly string $thumbnailUrl,
        private readonly int $duracao,
        private readonly MusicaStatus $status,
        private readonly ?int $userId,
        private readonly Carbon $createdAt,
        private readonly Carbon $updatedAt
    ) {}

    public static function create(
        string $titulo,
        string $artista,
        YouTubeUrl $youtubeUrl,
        int $visualizacoes,
        string $thumbnailUrl,
        int $duracao,
        ?int $userId = null
    ): self {
        return new self(
            id: null,
            titulo: $titulo,
            artista: $artista,
            youtubeUrl: $youtubeUrl,
            visualizacoes: $visualizacoes,
            thumbnailUrl: $thumbnailUrl,
            duracao: $duracao,
            status: MusicaStatus::PENDENTE,
            userId: $userId,
            createdAt: Carbon::now(),
            updatedAt: Carbon::now()
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            titulo: $data['titulo'],
            artista: $data['artista'],
            youtubeUrl: YouTubeUrl::fromString($data['youtube_url']),
            visualizacoes: $data['visualizacoes'],
            thumbnailUrl: $data['thumbnail_url'],
            duracao: $data['duracao'],
            status: MusicaStatus::from($data['status']),
            userId: $data['user_id'] ?? null,
            createdAt: Carbon::parse($data['created_at']),
            updatedAt: Carbon::parse($data['updated_at'])
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'artista' => $this->artista,
            'youtube_url' => $this->youtubeUrl->toString(),
            'youtube_id' => $this->youtubeUrl->getId(),
            'visualizacoes' => $this->visualizacoes,
            'visualizacoes_formatadas' => $this->getVisualizacoesFormatadas(),
            'thumbnail_url' => $this->thumbnailUrl,
            'duracao' => $this->duracao,
            'duracao_formatada' => $this->getDuracaoFormatada(),
            'status' => $this->status->value,
            'status_label' => $this->status->getLabel(),
            'user_id' => $this->userId,
            'created_at' => $this->createdAt->toISOString(),
            'updated_at' => $this->updatedAt->toISOString(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getArtista(): string
    {
        return $this->artista;
    }

    public function getYoutubeUrl(): YouTubeUrl
    {
        return $this->youtubeUrl;
    }

    public function getVisualizacoes(): int
    {
        return $this->visualizacoes;
    }

    public function getVisualizacoesFormatadas(): string
    {
        $numero = $this->visualizacoes;
        
        if ($numero >= 1000000000) {
            return number_format($numero / 1000000000, 1) . 'B';
        }
        if ($numero >= 1000000) {
            return number_format($numero / 1000000, 1) . 'M';
        }
        if ($numero >= 1000) {
            return number_format($numero / 1000, 1) . 'K';
        }
        
        return (string) $numero;
    }

    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    public function getDuracao(): int
    {
        return $this->duracao;
    }

    public function getDuracaoFormatada(): string
    {
        $minutos = intval($this->duracao / 60);
        $segundos = $this->duracao % 60;
        
        return sprintf('%d:%02d', $minutos, $segundos);
    }

    public function getStatus(): MusicaStatus
    {
        return $this->status;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function isAprovada(): bool
    {
        return $this->status === MusicaStatus::APROVADA;
    }

    public function isPendente(): bool
    {
        return $this->status === MusicaStatus::PENDENTE;
    }

    public function isReprovada(): bool
    {
        return $this->status === MusicaStatus::REPROVADA;
    }

    public function aprovar(): self
    {
        return new self(
            $this->id,
            $this->titulo,
            $this->artista,
            $this->youtubeUrl,
            $this->visualizacoes,
            $this->thumbnailUrl,
            $this->duracao,
            MusicaStatus::APROVADA,
            $this->userId,
            $this->createdAt,
            Carbon::now()
        );
    }

    public function reprovar(): self
    {
        return new self(
            $this->id,
            $this->titulo,
            $this->artista,
            $this->youtubeUrl,
            $this->visualizacoes,
            $this->thumbnailUrl,
            $this->duracao,
            MusicaStatus::REPROVADA,
            $this->userId,
            $this->createdAt,
            Carbon::now()
        );
    }

    public function atualizar(
        string $titulo,
        string $artista,
        YouTubeUrl $youtubeUrl,
        int $visualizacoes,
        string $thumbnailUrl,
        int $duracao
    ): self {
        return new self(
            $this->id,
            $titulo,
            $artista,
            $youtubeUrl,
            $visualizacoes,
            $thumbnailUrl,
            $duracao,
            $this->status,
            $this->userId,
            $this->createdAt,
            Carbon::now()
        );
    }
}