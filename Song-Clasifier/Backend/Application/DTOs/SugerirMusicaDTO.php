<?php

declare(strict_types=1);

namespace App\Application\DTOs;

final readonly class SugerirMusicaDTO
{
    public function __construct(
        public string $youtubeUrl,
        public ?int $userId = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            youtubeUrl: $data['youtube_url'] ?? '',
            userId: $data['user_id'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'youtube_url' => $this->youtubeUrl,
            'user_id' => $this->userId,
        ];
    }
}