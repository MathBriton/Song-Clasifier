<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Musica;
use App\Domain\ValueObjects\YouTubeUrl;
use App\Domain\ValueObjects\MusicaStatus;
use PHPUnit\Framework\TestCase;

class MusicaTest extends TestCase
{
    public function test_pode_criar_musica()
    {
        $youtubeUrl = YouTubeUrl::fromString('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        
        $musica = Musica::create(
            titulo: 'Boi Soberano',
            artista: 'Tião Carreiro & Pardinho',
            youtubeUrl: $youtubeUrl,
            visualizacoes: 1000000,
            thumbnailUrl: 'https://example.com/thumb.jpg',
            duracao: 180
        );

        $this->assertInstanceOf(Musica::class, $musica);
        $this->assertEquals('Boi Soberano', $musica->getTitulo());
        $this->assertEquals('Tião Carreiro & Pardinho', $musica->getArtista());
        $this->assertEquals(1000000, $musica->getVisualizacoes());
        $this->assertEquals('1.0M', $musica->getVisualizacoesFormatadas());
        $this->assertEquals('3:00', $musica->getDuracaoFormatada());
        $this->assertTrue($musica->isPendente());
    }

    public function test_pode_aprovar_musica()
    {
        $youtubeUrl = YouTubeUrl::fromString('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $musica = Musica::create('Test', 'Artist', $youtubeUrl, 1000, 'thumb.jpg', 180);

        $musicaAprovada = $musica->aprovar();

        $this->assertTrue($musicaAprovada->isAprovada());
        $this->assertFalse($musicaAprovada->isPendente());
    }

    public function test_pode_reprovar_musica()
    {
        $youtubeUrl = YouTubeUrl::fromString('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $musica = Musica::create('Test', 'Artist', $youtubeUrl, 1000, 'thumb.jpg', 180);

        $musicaReprovada = $musica->reprovar();

        $this->assertTrue($musicaReprovada->isReprovada());
        $this->assertFalse($musicaReprovada->isPendente());
    }

    public function test_formatacao_visualizacoes()
    {
        $youtubeUrl = YouTubeUrl::fromString('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        
        $casos = [
            [999, '999'],
            [1000, '1.0K'],
            [1500, '1.5K'],
            [1000000, '1.0M'],
            [2500000, '2.5M'],
            [1000000000, '1.0B'],
        ];

        foreach ($casos as [$visualizacoes, $esperado]) {
            $musica = Musica::create('Test', 'Artist', $youtubeUrl, $visualizacoes, 'thumb.jpg', 180);
            $this->assertEquals($esperado, $musica->getVisualizacoesFormatadas());
        }
    }
}