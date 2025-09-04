<?php

namespace App\Domain\Exceptions;

use Exception;

final class AcessoNegadoException extends Exception
{
    public function __construct(string $message = 'Acesso negado', int $code = 403)
    {
        parent::__construct($message, $code);
    }
}
