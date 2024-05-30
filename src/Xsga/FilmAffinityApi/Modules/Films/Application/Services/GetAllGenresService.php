<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\GenreDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\GenreToGenreDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\GenresRepository;

final class GetAllGenresService
{
    public function __construct(
        private LoggerInterface $logger,
        private GenresRepository $genresRepository,
        private GenreToGenreDto $mapper
    ) {
    }

    /**
     * @return GenreDto[]
     */
    public function get(): array
    {
        return $this->mapper->convertArray($this->genresRepository->getAll());
    }
}
