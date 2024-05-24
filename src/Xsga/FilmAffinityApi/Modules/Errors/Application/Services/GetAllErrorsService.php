<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Application\Services;

use Xsga\FilmAffinityApi\Modules\Errors\Application\Dto\ErrorDto;

interface GetAllErrorsService
{
    /**
     * @return ErrorDto[]
     */
    public function get(): array;
}
