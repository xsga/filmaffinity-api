<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Application\Services;

use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Mappers\ErrorToErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Services\GetError;

final class GetErrorService
{
    public function __construct(
        private GetError $getError,
        private ErrorToErrorDto $mapper
    ) {
    }

    public function get(int $code): ErrorDto
    {
        return $this->mapper->convert($this->getError->byCodeWithErrorWhenNotFound($code));
    }
}
