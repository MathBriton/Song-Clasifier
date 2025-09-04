<?php

namespace App\Domain\Services;

interface JwtServiceInterface
{
    /**
     * Gerar token JWT
     */
    public function generateToken(array $payload): string;

    /**
     * Gerar refresh token
     */
    public function generateRefreshToken(int $userId): string;

    /**
     * Validar token JWT
     */
    public function validateToken(string $token): array;

    /**
     * Validar refresh token
     */
    public function validateRefreshToken(string $refreshToken): array;

    /**
     * Invalidar token
     */
    public function invalidateToken(string $token): void;

    /**
     * Invalidar refresh token
     */
    public function invalidateRefreshToken(string $refreshToken): void;

    /**
     * Obter TTL do token em minutos
     */
    public function getTtl(): int;

    /**
     * Extrair payload do token sem validar
     */
    public function parseToken(string $token): array;
}