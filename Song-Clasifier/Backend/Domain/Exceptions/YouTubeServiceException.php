<?php

namespace App\Domain\Exceptions;

use Exception;

final class YouTubeServiceException extends Exception
{
    public function __construct(string $message = 'Erro ao acessar serviço do YouTube', int $code = 502)
    {
        parent::__construct($message, $code);
    }
}