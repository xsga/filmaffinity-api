<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;

final class JsonGenreToGenre
{
    public function convert(array $jsonGenre): Genre
    {
        $genre              = new Genre();
        $genre->code        = $jsonGenre['genre_code'];
        $genre->description = $jsonGenre['genre_desc'];

        return $genre;
    }

    /**
     * @return Genre[]
     */
    public function convertArray(array $jsonGenres): array
    {
        $out = [];

        foreach ($jsonGenres as $jsonGenre) {
            $out[] = $this->convert($jsonGenre);
        }

        return $out;
    }
}
