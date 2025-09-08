<?php

namespace App\Repositories\Interfaces;

use App\Models\Musica;
use App\Models\ValueObjects\MusicaStatus;
use App\Models\ValueObjects\YouTubeUrl;

interface MusicaRepositoryInterface
{
    /**
     * Buscar música por ID
     */
    public function findById(int $id): ?Musica;

    /**
     * Buscar música por URL do YouTube
     */
    public function findByYouTubeUrl(YouTubeUrl $youtubeUrl): ?Musica;

    /**
     * Buscar música por ID do YouTube
     */
    public function findByYouTubeId(string $youtubeId): ?Musica;

    /**
     * Listar top 5 músicas mais tocadas (apenas aprovadas)
     */
    public function getTop5(): array;

    /**
     * Listar músicas com paginação
     */
    public function paginate(
        int $page = 1,
        int $perPage = 15,
        ?MusicaStatus $status = null,
        ?string $search = null,
        string $orderBy = 'visualizacoes',
        string $orderDirection = 'desc'
    ): array;

    /**
     * Listar sugestões pendentes
     */
    public function getSugestoesPendentes(int $page = 1, int $perPage = 15): array;

    /**
     * Contar músicas por status
     */
    public function countByStatus(?MusicaStatus $status = null): int;

    /**
     * Salvar música (criar ou atualizar)
     */
    public function save(Musica $musica): Musica;

    /**
     * Criar nova música
     */
    public function create(Musica $musica): Musica;

    /**
     * Atualizar música existente
     */
    public function update(Musica $musica): Musica;

    /**
     * Excluir música por ID
     */
    public function delete(int $id): bool;

    /**
     * Verificar se existe música com esta URL
     */
    public function existsByYouTubeUrl(YouTubeUrl $youtubeUrl): bool;

    /**
     * Buscar músicas por artista
     */
    public function findByArtista(string $artista, int $limit = 10): array;

    /**
     * Buscar músicas mais recentes
     */
    public function getRecentes(int $limit = 10): array;

    /**
     * Buscar músicas populares (mais visualizações)
     */
    public function getPopulares(int $limit = 10): array;

    /**
     * Atualizar visualizações de uma música
     */
    public function updateVisualizacoes(int $id, int $novasVisualizacoes): bool;

    /**
     * Aprovar música
     */
    public function aprovar(int $id): bool;

    /**
     * Reprovar música
     */
    public function reprovar(int $id): bool;

    /**
     * Buscar estatísticas gerais
     */
    public function getEstatisticas(): array;
}
