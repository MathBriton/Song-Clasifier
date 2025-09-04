<?php

namespace App\Domain\Exceptions;

use Exception;

final class MusicaNaoEncontradaException extends Exception
{
    public function __construct(string $message = 'Música não encontrada', int $code = 404)
    {
        parent::__construct($message, $code);
    }
}