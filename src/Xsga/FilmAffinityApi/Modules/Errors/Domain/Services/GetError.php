<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Errors\Domain\Services;

use Xsga\FilmAffinityApi\Modules\Errors\Domain\Model\Error;

interface GetError
{
    public function byCode(int $code): ?Error;
    public function byCodeAndErrorNotFound(int $code): Error;
}
