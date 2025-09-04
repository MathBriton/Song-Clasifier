<?php

namespace App\Domain\Exceptions;

use Exception;

final class CredenciaisInvalidasException extends Exception
{
    public function __construct(string $message = 'Credenciais inválidas', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}