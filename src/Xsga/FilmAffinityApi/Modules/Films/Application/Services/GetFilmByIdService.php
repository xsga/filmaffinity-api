<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\FilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\FilmToFilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\FilmsRepository;

final class GetFilmByIdService
{
    public function __construct(
        private FilmsRepository $repository,
        private FilmToFilmDto $mapper
    ) {
    }

    public function get(int $filmId): FilmDto
    {
        return $this->mapper->convert($this->repository->get($filmId));
    }
}
