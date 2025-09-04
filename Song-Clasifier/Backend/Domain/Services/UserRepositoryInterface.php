<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;

interface UserRepositoryInterface
{
    /**
     * Buscar usuário por ID
     */
    public function findById(int $id): ?User;

    /**
     * Buscar usuário por email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Criar novo usuário
     */
    public function create(User $user): User;

    /**
     * Atualizar usuário
     */
    public function update(User $user): User;

    /**
     * Excluir usuário
     */
    public function delete(int $id): bool;

    /**
     * Verificar se email já existe
     */
    public function existsByEmail(string $email): bool;

    /**
     * Listar usuários com paginação
     */
    public function paginate(int $page = 1, int $perPage = 15): array;

    /**
     * Registrar login do usuário
     */
    public function registrarLogin(int $userId): void;

    /**
     * Obter último login do usuário
     */
    public function obterUltimoLogin(int $userId): ?string;
}