<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Infrastructure\Database\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário administrador padrão
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@tiaocarreiro.com.br',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Usuário comum para testes
        User::create([
            'name' => 'João da Silva',
            'email' => 'joao@exemplo.com',
            'password' => Hash::make('123456'),
            'is_admin' => false,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}