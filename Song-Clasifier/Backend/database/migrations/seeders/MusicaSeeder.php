<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Database\Models\Musica;

class MusicaSeeder extends Seeder
{
    public function run(): void
    {
        $musicas = [
            [
                'titulo' => 'Boi Soberano - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'youtube_id' => 'dQw4w9WgXcQ',
                'visualizacoes' => 45900000,
                'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'duracao' => 213,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'Rei do Gado - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example2',
                'youtube_id' => 'example2',
                'visualizacoes' => 38200000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example2/maxresdefault.jpg',
                'duracao' => 195,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'Pagode em Brasília - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example3',
                'youtube_id' => 'example3',
                'visualizacoes' => 35100000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example3/maxresdefault.jpg',
                'duracao' => 225,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'Peão de Boiadeiro - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example4',
                'youtube_id' => 'example4',
                'visualizacoes' => 29800000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example4/maxresdefault.jpg',
                'duracao' => 180,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'Chico Mineiro - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example5',
                'youtube_id' => 'example5',
                'visualizacoes' => 27500000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example5/maxresdefault.jpg',
                'duracao' => 208,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'Moda da Pinga - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example6',
                'youtube_id' => 'example6',
                'visualizacoes' => 24300000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example6/maxresdefault.jpg',
                'duracao' => 192,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'Cabocla Tereza - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example7',
                'youtube_id' => 'example7',
                'visualizacoes' => 22100000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example7/maxresdefault.jpg',
                'duracao' => 175,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'Couro de Boi - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example8',
                'youtube_id' => 'example8',
                'visualizacoes' => 19900000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example8/maxresdefault.jpg',
                'duracao' => 205,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'O Menino da Porteira - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example9',
                'youtube_id' => 'example9',
                'visualizacoes' => 18700000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example9/maxresdefault.jpg',
                'duracao' => 188,
                'status' => 'aprovada',
            ],
            [
                'titulo' => 'Beijinho Doce - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=example10',
                'youtube_id' => 'example10',
                'visualizacoes' => 16500000,
                'thumbnail_url' => 'https://img.youtube.com/vi/example10/maxresdefault.jpg',
                'duracao' => 167,
                'status' => 'aprovada',
            ],
            // Algumas sugestões pendentes para teste
            [
                'titulo' => 'Boiadeiro Errante - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=pending1',
                'youtube_id' => 'pending1',
                'visualizacoes' => 5200000,
                'thumbnail_url' => 'https://img.youtube.com/vi/pending1/maxresdefault.jpg',
                'duracao' => 201,
                'status' => 'pendente',
                'user_id' => 2,
            ],
            [
                'titulo' => 'Saudade de Minha Terra - Tião Carreiro e Pardinho',
                'artista' => 'Tião Carreiro & Pardinho',
                'youtube_url' => 'https://www.youtube.com/watch?v=pending2',
                'youtube_id' => 'pending2',
                'visualizacoes' => 3800000,
                'thumbnail_url' => 'https://img.youtube.com/vi/pending2/maxresdefault.jpg',
                'duracao' => 186,
                'status' => 'pendente',
                'user_id' => 2,
            ],
        ];

        foreach ($musicas as $musica) {
            Musica::create($musica);
        }
    }
}