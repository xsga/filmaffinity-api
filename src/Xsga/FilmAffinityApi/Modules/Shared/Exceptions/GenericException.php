<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Exceptions;

use Exception;
use Throwable;

class GenericException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null,
        protected array $params = []
    ) {
        parent::__construct($message, $code, $previous);
    }

    final public function getParams(): array
    {
        return $this->params;
    }
}
