<?php
// backend/app/Domain/Entities/User.php

declare(strict_types=1);

namespace App\Domain\Entities;

use Carbon\Carbon;

final class User
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $name,
        private readonly string $email,
        private readonly string $password,
        private readonly bool $isAdmin,
        private readonly bool $isActive,
        private readonly ?Carbon $emailVerifiedAt,
        private readonly Carbon $createdAt,
        private readonly Carbon $updatedAt
    ) {}

    public static function create(
        string $name,
        string $email,
        string $password,
        bool $isAdmin = false
    ): self {
        return new self(
            id: null,
            name: $name,
            email: $email,
            password: password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            isAdmin: $isAdmin,
            isActive: true,
            emailVerifiedAt: null,
            createdAt: Carbon::now(),
            updatedAt: Carbon::now()
        );
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            isAdmin: (bool) $data['is_admin'],
            isActive: (bool) ($data['is_active'] ?? true),
            emailVerifiedAt: $data['email_verified_at'] ? Carbon::parse($data['email_verified_at']) : null,
            createdAt: Carbon::parse($data['created_at']),
            updatedAt: Carbon::parse($data['updated_at'])
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->isAdmin,
            'is_active' => $this->isActive,
            'email_verified_at' => $this->emailVerifiedAt?->toISOString(),
            'created_at' => $this->createdAt->toISOString(),
            'updated_at' => $this->updatedAt->toISOString(),
        ];
    }

    public function toJwtPayload(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->isAdmin,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->emailVerifiedAt;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerifiedAt !== null;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function updatePassword(string $newPassword): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]),
            $this->isAdmin,
            $this->isActive,
            $this->emailVerifiedAt,
            $this->createdAt,
            Carbon::now()
        );
    }

    public function updateProfile(string $name, string $email): self
    {
        return new self(
            $this->id,
            $name,
            $email,
            $this->password,
            $this->isAdmin,
            $this->isActive,
            $this->emailVerifiedAt,
            $this->createdAt,
            Carbon::now()
        );
    }

    public function activate(): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->password,
            $this->isAdmin,
            true,
            $this->emailVerifiedAt,
            $this->createdAt,
            Carbon::now()
        );
    }

    public function deactivate(): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->password,
            $this->isAdmin,
            false,
            $this->emailVerifiedAt,
            $this->createdAt,
            Carbon::now()
        );
    }

    public function verifyEmail(): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->password,
            $this->isAdmin,
            $this->isActive,
            Carbon::now(),
            $this->createdAt,
            Carbon::now()
        );
    }
}