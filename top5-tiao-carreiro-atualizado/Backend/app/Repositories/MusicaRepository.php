<?php

namespace App\Repositories;

use App\Models\Musica;
use App\Models\ValueObjects\MusicaStatus;
use App\Models\ValueObjects\YouTubeUrl;
use App\Repositories\Interfaces\MusicaRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MusicaRepository implements MusicaRepositoryInterface
{
    protected string $table = 'musicas';

    /**
     * Buscar música por ID
     */
    public function findById(int $id): ?Musica
    {
        try {
            $data = DB::table($this->table)->where('id', $id)->first();

            return $data ? $this->mapToModel($data) : null;
        } catch (\Exception $e) {
            Log::error('Erro ao buscar música por ID', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Buscar música por URL do YouTube
     */
    public function findByYouTubeUrl(YouTubeUrl $youtubeUrl): ?Musica
    {
        try {
            $data = DB::table($this->table)->where('youtube_url', $youtubeUrl->toString())->first();

            return $data ? $this->mapToModel($data) : null;
        } catch (\Exception $e) {
            Log::error('Erro ao buscar música por URL do YouTube', [
                'url' => $youtubeUrl->toString(),
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Buscar música por ID do YouTube
     */
    public function findByYouTubeId(string $youtubeId): ?Musica
    {
        try {
            $data = DB::table($this->table)->where('youtube_id', $youtubeId)->first();

            return $data ? $this->mapToModel($data) : null;
        } catch (\Exception $e) {
            Log::error('Erro ao buscar música por ID do YouTube', [
                'youtube_id' => $youtubeId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Listar top 5 músicas mais tocadas (apenas aprovadas)
     */
    public function getTop5(): array
    {
        try {
            $results = DB::table($this->table)
                ->where('status', MusicaStatus::APROVADA->value)
                ->orderBy('visualizacoes', 'desc')
                ->limit(5)
                ->get();

            return $this->mapCollectionToArray($results);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar top 5 músicas', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

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
    ): array {
        try {
            $query = DB::table($this->table);

            // Filtro por status
            if ($status) {
                $query->where('status', $status->value);
            }

            // Filtro de busca
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('titulo', 'LIKE', "%{$search}%")
                      ->orWhere('artista', 'LIKE', "%{$search}%");
                });
            }

            // Contagem total
            $total = $query->count();

            // Ordenação e paginação
            $offset = ($page - 1) * $perPage;
            $items = $query->orderBy($orderBy, $orderDirection)
                          ->offset($offset)
                          ->limit($perPage)
                          ->get();

            return [
                'data' => $this->mapCollectionToArray($items),
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => ceil($total / $perPage),
                    'has_next' => $page < ceil($total / $perPage),
                    'has_previous' => $page > 1
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao paginar músicas', [
                'page' => $page,
                'per_page' => $perPage,
                'error' => $e->getMessage()
            ]);
            return [
                'data' => [],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => 0,
                    'total_pages' => 0,
                    'has_next' => false,
                    'has_previous' => false
                ]
            ];
        }
    }

    /**
     * Listar sugestões pendentes
     */
    public function getSugestoesPendentes(int $page = 1, int $perPage = 15): array
    {
        try {
            return $this->paginate(
                page: $page,
                perPage: $perPage,
                status: MusicaStatus::PENDENTE,
                orderBy: 'created_at',
                orderDirection: 'asc'
            );
        } catch (\Exception $e) {
            Log::error('Erro ao buscar sugestões pendentes', [
                'error' => $e->getMessage()
            ]);
            return [
                'data' => [],
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => 0,
                    'total_pages' => 0,
                    'has_next' => false,
                    'has_previous' => false
                ]
            ];
        }
    }

    /**
     * Contar músicas por status
     */
    public function countByStatus(?MusicaStatus $status = null): int
    {
        try {
            $query = DB::table($this->table);

            if ($status) {
                $query->where('status', $status->value);
            }

            return $query->count();
        } catch (\Exception $e) {
            Log::error('Erro ao contar músicas por status', [
                'status' => $status?->value,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Salvar música (criar ou atualizar)
     */
    public function save(Musica $musica): Musica
    {
        try {
            DB::beginTransaction();

            if ($musica->getId()) {
                $this->updateRecord($musica);
                $id = $musica->getId();
            } else {
                $id = $this->insertRecord($musica);
            }

            DB::commit();

            // Retornar música atualizada do banco
            return $this->findById($id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao salvar música', [
                'musica_id' => $musica->getId(),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Criar nova música
     */
    public function create(Musica $musica): Musica
    {
        try {
            DB::beginTransaction();

            if ($musica->getId()) {
                throw new \InvalidArgumentException('Não é possível criar uma música que já possui ID');
            }

            $id = $this->insertRecord($musica);

            DB::commit();

            return $this->findById($id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar música', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Atualizar música existente
     */
    public function update(Musica $musica): Musica
    {
        try {
            DB::beginTransaction();

            if (!$musica->getId()) {
                throw new \InvalidArgumentException('Não é possível atualizar uma música sem ID');
            }

            $this->updateRecord($musica);

            DB::commit();

            return $this->findById($musica->getId());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar música', [
                'musica_id' => $musica->getId(),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Excluir música por ID
     */
    public function delete(int $id): bool
    {
        try {
            DB::beginTransaction();

            $deleted = DB::table($this->table)->where('id', $id)->delete() > 0;

            DB::commit();
            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao excluir música', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verificar se existe música com esta URL
     */
    public function existsByYouTubeUrl(YouTubeUrl $youtubeUrl): bool
    {
        try {
            return DB::table($this->table)->where('youtube_url', $youtubeUrl->toString())->exists();
        } catch (\Exception $e) {
            Log::error('Erro ao verificar existência por URL do YouTube', [
                'url' => $youtubeUrl->toString(),
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Buscar músicas por artista
     */
    public function findByArtista(string $artista, int $limit = 10): array
    {
        try {
            $results = DB::table($this->table)
                ->where('artista', 'LIKE', "%{$artista}%")
                ->where('status', MusicaStatus::APROVADA->value)
                ->orderBy('visualizacoes', 'desc')
                ->limit($limit)
                ->get();

            return $this->mapCollectionToArray($results);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar músicas por artista', [
                'artista' => $artista,
                'limit' => $limit,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Buscar músicas mais recentes
     */
    public function getRecentes(int $limit = 10): array
    {
        try {
            $results = DB::table($this->table)
                ->where('status', MusicaStatus::APROVADA->value)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return $this->mapCollectionToArray($results);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar músicas recentes', [
                'limit' => $limit,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Buscar músicas populares (mais visualizações)
     */
    public function getPopulares(int $limit = 10): array
    {
        try {
            $results = DB::table($this->table)
                ->where('status', MusicaStatus::APROVADA->value)
                ->orderBy('visualizacoes', 'desc')
                ->limit($limit)
                ->get();

            return $this->mapCollectionToArray($results);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar músicas populares', [
                'limit' => $limit,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Atualizar visualizações de uma música
     */
    public function updateVisualizacoes(int $id, int $novasVisualizacoes): bool
    {
        try {
            return DB::table($this->table)
                ->where('id', $id)
                ->update([
                    'visualizacoes' => $novasVisualizacoes,
                    'updated_at' => now()
                ]) > 0;
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar visualizações', [
                'id' => $id,
                'novas_visualizacoes' => $novasVisualizacoes,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Aprovar música
     */
    public function aprovar(int $id): bool
    {
        try {
            DB::beginTransaction();

            $updated = DB::table($this->table)
                ->where('id', $id)
                ->update([
                    'status' => MusicaStatus::APROVADA->value,
                    'updated_at' => now()
                ]) > 0;

            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao aprovar música', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Reprovar música
     */
    public function reprovar(int $id): bool
    {
        try {
            DB::beginTransaction();

            $updated = DB::table($this->table)
                ->where('id', $id)
                ->update([
                    'status' => MusicaStatus::REPROVADA->value,
                    'updated_at' => now()
                ]) > 0;

            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao reprovar música', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Buscar estatísticas gerais
     */
    public function getEstatisticas(): array
    {
        try {
            $totalMusicas = $this->countByStatus();
            $musicasAprovadas = $this->countByStatus(MusicaStatus::APROVADA);
            $musicasPendentes = $this->countByStatus(MusicaStatus::PENDENTE);
            $musicasReprovadas = $this->countByStatus(MusicaStatus::REPROVADA);

            $totalVisualizacoes = DB::table($this->table)
                ->where('status', MusicaStatus::APROVADA->value)
                ->sum('visualizacoes');

            $mediaVisualizacoes = $musicasAprovadas > 0
                ? round($totalVisualizacoes / $musicasAprovadas, 2)
                : 0;

            $musicaMaisPopular = DB::table($this->table)
                ->where('status', MusicaStatus::APROVADA->value)
                ->orderBy('visualizacoes', 'desc')
                ->first();

            return [
                'total_musicas' => $totalMusicas,
                'musicas_aprovadas' => $musicasAprovadas,
                'musicas_pendentes' => $musicasPendentes,
                'musicas_reprovadas' => $musicasReprovadas,
                'total_visualizacoes' => (int) $totalVisualizacoes,
                'media_visualizacoes' => $mediaVisualizacoes,
                'musica_mais_popular' => $musicaMaisPopular ? (array) $musicaMaisPopular : null,
                'porcentagem_aprovacao' => $totalMusicas > 0
                    ? round(($musicasAprovadas / $totalMusicas) * 100, 2)
                    : 0
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao buscar estatísticas', [
                'error' => $e->getMessage()
            ]);
            return [
                'total_musicas' => 0,
                'musicas_aprovadas' => 0,
                'musicas_pendentes' => 0,
                'musicas_reprovadas' => 0,
                'total_visualizacoes' => 0,
                'media_visualizacoes' => 0,
                'musica_mais_popular' => null,
                'porcentagem_aprovacao' => 0
            ];
        }
    }

    /**
     * Mapear dados do banco para objeto Musica
     */
    protected function mapToModel(\stdClass $data): Musica
    {
        return Musica::fromArray([
            'id' => (int) $data->id,
            'titulo' => $data->titulo,
            'artista' => $data->artista,
            'youtube_url' => $data->youtube_url,
            'visualizacoes' => (int) $data->visualizacoes,
            'thumbnail_url' => $data->thumbnail_url,
            'duracao' => (int) $data->duracao,
            'status' => $data->status,
            'user_id' => $data->user_id ? (int) $data->user_id : null,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
        ]);
    }

    /**
     * Mapear coleção de dados para array
     */
    protected function mapCollectionToArray($collection): array
    {
        return $collection->map(function ($item) {
            return $this->mapToModel($item)->toArray();
        })->toArray();
    }

    /**
     * Inserir novo registro
     */
    protected function insertRecord(Musica $musica): int
    {
        return DB::table($this->table)->insertGetId([
            'titulo' => $musica->getTitulo(),
            'artista' => $musica->getArtista(),
            'youtube_url' => $musica->getYoutubeUrl()->toString(),
            'youtube_id' => $musica->getYoutubeUrl()->getId(),
            'visualizacoes' => $musica->getVisualizacoes(),
            'thumbnail_url' => $musica->getThumbnailUrl(),
            'duracao' => $musica->getDuracao(),
            'status' => $musica->getStatus()->value,
            'user_id' => $musica->getUserId(),
            'created_at' => $musica->getCreatedAt()->toDateTimeString(),
            'updated_at' => $musica->getUpdatedAt()->toDateTimeString(),
        ]);
    }

    /**
     * Atualizar registro existente
     */
    protected function updateRecord(Musica $musica): bool
    {
        $data = [
            'titulo' => $musica->getTitulo(),
            'artista' => $musica->getArtista(),
            'youtube_url' => $musica->getYoutubeUrl()->toString(),
            'youtube_id' => $musica->getYoutubeUrl()->getId(),
            'visualizacoes' => $musica->getVisualizacoes(),
            'thumbnail_url' => $musica->getThumbnailUrl(),
            'duracao' => $musica->getDuracao(),
            'status' => $musica->getStatus()->value,
            'updated_at' => $musica->getUpdatedAt(),
        ];

        // Adiciona user_id apenas se não for null
        if ($musica->getUserId() !== null) {
            $data['user_id'] = $musica->getUserId();
        }

        return DB::table($this->table)
            ->where('id', $musica->getId())
            ->update($data) > 0;
    }
}
