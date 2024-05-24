<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Domain\Repositories;

use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;

interface ErrorsRepository
{
    /**
     * @return Error[]
     */
    public function getAllErrors(): array;

    public function getError(int $code): ?Error;
}
