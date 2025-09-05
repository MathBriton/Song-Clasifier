<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('musicas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('artista')->default('Tião Carreiro & Pardinho');
            $table->string('youtube_url');
            $table->string('youtube_id', 11)->unique();
            $table->bigInteger('visualizacoes')->default(0);
            $table->string('thumbnail_url');
            $table->integer('duracao'); // em segundos
            $table->enum('status', ['pendente', 'aprovada', 'reprovada'])->default('pendente');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            // Índices para performance
            $table->index(['status', 'visualizacoes']);
            $table->index(['youtube_id']);
            $table->index(['status', 'created_at']);
            $table->index(['user_id']);
            
            // Índice para busca de texto
            $table->index(['titulo', 'artista']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('musicas');
    }
};