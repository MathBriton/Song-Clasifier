<?php

namespace App\Models\ValueObjects;

enum MusicaStatus: string
{
    case PENDENTE = 'pendente';
    case APROVADA = 'aprovada';
    case REPROVADA = 'reprovada';

    public function getLabel(): string
    {
        return match($this) {
            self::PENDENTE => 'Pendente',
            self::APROVADA => 'Aprovada',
            self::REPROVADA => 'Reprovada',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PENDENTE => 'yellow',
            self::APROVADA => 'green',
            self::REPROVADA => 'red',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::PENDENTE => 'clock',
            self::APROVADA => 'check-circle',
            self::REPROVADA => 'x-circle',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::PENDENTE => 'Aguardando aprovação do administrador',
            self::APROVADA => 'Música aprovada e visível publicamente',
            self::REPROVADA => 'Música reprovada pelo administrador',
        };
    }

    public static function getAll(): array
    {
        return [
            self::PENDENTE->value => [
                'value' => self::PENDENTE->value,
                'label' => self::PENDENTE->getLabel(),
                'color' => self::PENDENTE->getColor(),
                'icon' => self::PENDENTE->getIcon(),
                'description' => self::PENDENTE->getDescription(),
            ],
            self::APROVADA->value => [
                'value' => self::APROVADA->value,
                'label' => self::APROVADA->getLabel(),
                'color' => self::APROVADA->getColor(),
                'icon' => self::APROVADA->getIcon(),
                'description' => self::APROVADA->getDescription(),
            ],
            self::REPROVADA->value => [
                'value' => self::REPROVADA->value,
                'label' => self::REPROVADA->getLabel(),
                'color' => self::REPROVADA->getColor(),
                'icon' => self::REPROVADA->getIcon(),
                'description' => self::REPROVADA->getDescription(),
            ],
        ];
    }
}
