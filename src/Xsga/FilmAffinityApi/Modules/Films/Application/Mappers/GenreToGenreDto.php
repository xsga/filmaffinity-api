<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\GenreDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;

class GenreToGenreDto
{
    public function convert(Genre $genre): GenreDto
    {
        $genreDto = new GenreDto();

        $genreDto->code = $genre->code();
        $genreDto->name = $genre->name();

        return $genreDto;
    }

    /**
     * @param Genre[] $genres
     * 
     * @return GenreDto[]
     */
    public function convertArray(array $genres): array
    {
        $out = [];

        foreach ($genres as $genre) {
            $out[] = $this->convert($genre);
        }

        return $out;
    }
}
