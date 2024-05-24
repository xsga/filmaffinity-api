<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Application\Services\Impl;

use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Mappers\ErrorToErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Services\GetErrorService;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Services\GetError;

final class GetErrorServiceImpl implements GetErrorService
{
    public function __construct(
        private GetError $getError,
        private ErrorToErrorDto $mapper
    ) {
    }

    public function get(int $code): ErrorDto
    {
        $error = $this->getError->byCodeAndErrorNotFound($code);

        return $this->mapper->convert($error);
    }
}
