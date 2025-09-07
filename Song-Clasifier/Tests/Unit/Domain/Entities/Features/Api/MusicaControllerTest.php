<?php

namespace Tests\Feature\Api;

use App\Infrastructure\Database\Models\User;
use App\Infrastructure\Database\Models\Musica;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MusicaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Criar dados de teste
        $this->seed();
    }

    public function test_pode_listar_top5_musicas()
    {
        $response = $this->getJson('/api/musicas/top5');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'titulo',
                            'artista',
                            'youtube_url',
                            'visualizacoes',
                            'visualizacoes_formatadas',
                            'thumbnail_url',
                            'duracao',
                            'duracao_formatada',
                            'status'
                        ]
                    ],
                    'total',
                    'message'
                ])
                ->assertJson([
                    'success' => true,
                    'total' => 5
                ]);
    }

    public function test_pode_listar_musicas_com_paginacao()
    {
        $response = $this->getJson('/api/musicas?page=1&per_page=5');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [],
                        'pagination' => [
                            'current_page',
                            'per_page',
                            'total',
                            'total_pages'
                        ]
                    ]
                ]);
    }

    public function test_pode_sugerir_musica()
    {
        $response = $this->postJson('/api/musicas/sugerir', [
            'youtube_url' => 'https://www.youtube.com/watch?v=teste123'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'id',
                        'titulo',
                        'status'
                    ],
                    'message'
                ])
                ->assertJson([
                    'success' => true
                ]);
    }

    public function test_nao_pode_sugerir_musica_com_url_invalida()
    {
        $response = $this->postJson('/api/musicas/sugerir', [
            'youtube_url' => 'url-invalida'
        ]);

        $response->assertStatus(422);
    }

    public function test_nao_pode_sugerir_musica_duplicada()
    {
        // Primeira sugestão
        $this->postJson('/api/musicas/sugerir', [
            'youtube_url' => 'https://www.youtube.com/watch?v=unique123'
        ]);

        // Segunda sugestão (deve falhar)
        $response = $this->postJson('/api/musicas/sugerir', [
            'youtube_url' => 'https://www.youtube.com/watch?v=unique123'
        