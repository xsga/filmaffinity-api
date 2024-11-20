<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\FilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\FilmToFilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Exceptions\FilmNotFoundException;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\FilmsRepository;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Domain\Exceptions\PageNotFoundException;

final class GetFilmByIdService
{
    public function __construct(
        private FilmsRepository $repository,
        private FilmToFilmDto $mapper
    ) {
    }

    public function get(int $filmId): FilmDto
    {
        try {
            return $this->mapper->convert($this->repository->get($filmId));
        } catch (PageNotFoundException $exception) {
            throw new FilmNotFoundException("Film with ID '$filmId' not found", 2005, null, [1 => $filmId]);
        }
    }
}
