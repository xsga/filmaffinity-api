<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;

interface GenresRepository
{
    /**
     * @return Genre[]
     */
    public function getAll(): array;

    public function get(string $code): ?Genre;
}
