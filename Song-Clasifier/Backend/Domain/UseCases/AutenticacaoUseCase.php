<?php
// backend/app/Domain/UseCases/AutenticacaoUseCase.php

declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Services\JwtServiceInterface;
use App\Domain\Exceptions\CredenciaisInvalidasException;
use App\Domain\Exceptions\UsuarioInativoException;

final class AutenticacaoUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly JwtServiceInterface $jwtService
    ) {}

    public function login(string $email, string $password): array
    {
        // Buscar usuário por email
        $user = $this->userRepository->findByEmail($email);
        
        if (!$user) {
            throw new CredenciaisInvalidasException('Credenciais inválidas');
        }

        // Verificar senha
        if (!$user->verifyPassword($password)) {
            throw new CredenciaisInvalidasException('Credenciais inválidas');
        }

        // Verificar se usuário está ativo
        if (!$user->isActive()) {
            throw new UsuarioInativoException('Usuário está inativo');
        }

        // Gerar token JWT
        $token = $this->jwtService->generateToken($user->toJwtPayload());
        $refreshToken = $this->jwtService->generateRefreshToken($user->getId());

        // Registrar login
        $this->userRepository->registrarLogin($user->getId());

        return [
            'success' => true,
            'data' => [
                'user' => $user->toArray(),
                'token' => $token,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'expires_in' => $this->jwtService->getTtl() * 60
            ],
            'message' => 'Login realizado com sucesso'
        ];
    }

    public function logout(string $token): array
    {
        $this->jwtService->invalidateToken($token);

        return [
            'success' => true,
            'message' => 'Logout realizado com sucesso'
        ];
    }

    public function refresh(string $refreshToken): array
    {
        $payload = $this->jwtService->validateRefreshToken($refreshToken);
        
        $user = $this->userRepository->findById($payload['user_id']);
        
        if (!$user || !$user->isActive()) {
            throw new CredenciaisInvalidasException('Token de refresh inválido');
        }

        // Gerar novo token
        $newToken = $this->jwtService->generateToken($user->toJwtPayload());
        $newRefreshToken = $this->jwtService->generateRefreshToken($user->getId());

        // Invalidar refresh token antigo
        $this->jwtService->invalidateRefreshToken($refreshToken);

        return [
            'success' => true,
            'data' => [
                'token' => $newToken,
                'refresh_token' => $newRefreshToken,
                'token_type' => 'Bearer',
                'expires_in' => $this->jwtService->getTtl() * 60
            ],
            'message' => 'Token renovado com sucesso'
        ];
    }

    public function me(string $token): array
    {
        $payload = $this->jwtService->validateToken($token);
        
        $user = $this->userRepository->findById($payload['id']);
        
        if (!$user) {
            throw new CredenciaisInvalidasException('Token inválido');
        }

        return [
            'success' => true,
            'data' => $user->toArray(),
            'message' => 'Dados do usuário obtidos com sucesso'
        ];
    }
}