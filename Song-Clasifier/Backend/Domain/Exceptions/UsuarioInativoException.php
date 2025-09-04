<?php

namespace App\Domain\Exceptions;

use Exception;

final class UsuarioInativoException extends Exception
{
    public function __construct(string $message = 'Usuário inativo', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}