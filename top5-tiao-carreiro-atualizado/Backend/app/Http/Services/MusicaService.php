<?php

namespace App\Services;

use App\Models\Musica;
use App\Models\ValueObjects\MusicaStatus;
use App\Models\ValueObjects\YouTubeUrl;
use App\Repositories\Interfaces\MusicaRepositoryInterface;

class MusicaService
{
    public function __construct(
        private MusicaRepositoryInterface $musicaRepository
    ) {}

    /**
     * Listar músicas com paginação e filtros
     * Normaliza a resposta do repositório para { data, pagination }.
     */
    public function listarMusicasPaginadas(
        int $page = 1,
        int $perPage = 15,
        ?string $status = null,
        ?string $search = null,
        string $orderBy = 'visualizacoes',
        string $orderDirection = 'desc'
    ): array {
        $statusEnum = $status ? MusicaStatus::from($status) : null;

        $result = $this->musicaRepository->paginate(
            page: $page,
            perPage: $perPage,
            status: $statusEnum,
            search: $search,
            orderBy: $orderBy,
            orderDirection: $orderDirection
        );

        // Caso o repo já devolva no formato esperado, só retorna.
        if (isset($result['data']) && isset($result['pagination'])) {
            // Garante que data sejam arrays serializáveis
            $result['data'] = array_map(
                fn ($m) => is_array($m) ? $m : (method_exists($m, 'toArray') ? $m->toArray() : $m),
                $result['data']
            );
            return $result;
        }

        // Caso seja o array padrão do paginator do Laravel,
        // normaliza para { data, pagination }.
        $data = $result['data'] ?? [];
        $data = array_map(
            fn ($m) => is_array($m) ? $m : (method_exists($m, 'toArray') ? $m->toArray() : $m),
            $data
        );

        $pagination = [
            'current_page' => $result['current_page'] ?? $page,
            'per_page'     => $result['per_page'] ?? $perPage,
            'last_page'    => $result['last_page'] ?? null,
            'total'        => $result['total'] ?? count($data),
        ];

        return [
            'data' => $data,
            'pagination' => $pagination,
        ];
    }

    /**
     * Criar nova música
     */
    public function criarMusica(array $dados): Musica
    {
        $yt = YouTubeUrl::fromString($dados['youtube_url']);

        if ($this->musicaRepository->existsByYouTubeUrl($yt)) {
            throw new \Exception('Já existe uma música com essa URL do YouTube');
        }

        // Defaults conservadores
        $visualizacoes = isset($dados['visualizacoes']) ? (int) $dados['visualizacoes'] : 0;
        $duracao       = isset($dados['duracao']) ? (int) $dados['duracao'] : 0;
        $thumbnailUrl  = $dados['thumbnail_url'] ?? $yt->getThumbnailUrl(); // pega do YouTube por padrão
        $userId        = $dados['user_id'] ?? null;

        $musica = Musica::create(
            titulo: $dados['titulo'],
            artista: $dados['artista'],
            youtubeUrl: $yt,
            visualizacoes: $visualizacoes,
            thumbnailUrl: $thumbnailUrl,
            duracao: $duracao,
            userId: $userId
        );

        return $this->musicaRepository->create($musica);
    }

    /**
     * Buscar música por ID
     */
    public function findById(int $id): Musica
    {
        $musica = $this->musicaRepository->findById($id);

        if (!$musica) {
            throw new \Exception('Música não encontrada');
        }

        return $musica;
    }

    /**
     * Atualizar música existente (imutável -> retorna nova instância)
     */
    public function atualizarMusica(int $id, array $dados): Musica
    {
        $musica = $this->findById($id);

        $novoYoutubeUrl = isset($dados['youtube_url'])
            ? YouTubeUrl::fromString($dados['youtube_url'])
            : $musica->getYoutubeUrl();

        // Se trocou a URL e ela já existe em outra música, barra.
        if (isset($dados['youtube_url'])) {
            $urlAlterou = !$musica->getYoutubeUrl()->equals($novoYoutubeUrl);
            if ($urlAlterou && $this->musicaRepository->existsByYouTubeUrl($novoYoutubeUrl)) {
                throw new \Exception('Já existe uma música com essa URL do YouTube');
            }
        }

        $atualizada = $musica->atualizar(
            titulo: $dados['titulo'] ?? $musica->getTitulo(),
            artista: $dados['artista'] ?? $musica->getArtista(),
            youtubeUrl: $novoYoutubeUrl,
            visualizacoes: isset($dados['visualizacoes']) ? (int) $dados['visualizacoes'] : $musica->getVisualizacoes(),
            thumbnailUrl: $dados['thumbnail_url'] ?? $musica->getThumbnailUrl(),
            duracao: isset($dados['duracao']) ? (int) $dados['duracao'] : $musica->getDuracao(),
        );

        return $this->musicaRepository->update($atualizada);
    }

    /**
     * Excluir música
     */
    public function excluirMusica(int $id): bool
    {
        if (!$this->musicaRepository->findById($id)) {
            throw new \Exception('Música não encontrada');
        }

        return $this->musicaRepository->delete($id);
    }

    /**
     * Top 5 músicas aprovadas
     */
    public function getTop5(): array
    {
        return $this->musicaRepository->getTop5();
    }

    /**
     * Músicas mais recentes
     */
    public function getMusicasRecentes(int $limit = 10): array
    {
        return $this->musicaRepository->getRecentes($limit);
    }

    /**
     * Músicas mais populares
     */
    public function getMusicasPopulares(int $limit = 10): array
    {
        return $this->musicaRepository->getPopulares($limit);
    }

    /**
     * Buscar por artista
     */
    public function buscarPorArtista(string $artista, int $limit = 10): array
    {
        return $this->musicaRepository->findByArtista($artista, $limit);
    }

    /**
     * Incrementar visualizações
     */
    public function incrementarVisualizacoes(int $id): bool
    {
        $musica = $this->findById($id);
        $novas = $musica->getVisualizacoes() + 1;
        return $this->musicaRepository->updateVisualizacoes($id, $novas);
    }

    /**
     * Buscar por URL do YouTube
     */
    public function buscarPorYouTubeUrl(string $url): ?Musica
    {
        $yt = YouTubeUrl::fromString($url);
        return $this->musicaRepository->findByYouTubeUrl($yt);
    }

    // (Opcional) Ações de moderação
    public function aprovar(int $id): bool
    {
        return $this->musicaRepository->aprovar($id);
    }

    public function reprovar(int $id): bool
    {
        return $this->musicaRepository->reprovar($id);
    }

    public function getEstatisticas(): array
    {
        return $this->musicaRepository->getEstatisticas();
    }
}
