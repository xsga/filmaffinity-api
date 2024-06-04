<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Film;

interface FilmsRepository
{
    public function get(int $filmId): Film;
}
