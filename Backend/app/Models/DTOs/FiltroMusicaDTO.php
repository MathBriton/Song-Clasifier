<?php

namespace App\Models\DTOs;

use App\Models\ValueObjects\MusicaStatus;

final readonly class FiltroMusicaDTO
{
    public function __construct(
        public int $page = 1,
        public int $perPage = 15,
        public ?string $search = null,
        public ?MusicaStatus $status = null,
        public string $orderBy = 'visualizacoes',
        public string $orderDirection = 'desc'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            page: (int) ($data['page'] ?? 1),
            perPage: (int) ($data['per_page'] ?? 15),
            search: $data['search'] ?? null,
            status: isset($data['status']) ? MusicaStatus::from($data['status']) : null,
            orderBy: $data['order_by'] ?? 'visualizacoes',
            orderDirection: $data['order_direction'] ?? 'desc'
        );
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'per_page' => $this->perPage,
            'search' => $this->search,
            'status' => $this->status?->value,
            'order_by' => $this->orderBy,
            'order_direction' => $this->orderDirection,
        ];
    }
}
