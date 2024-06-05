<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\FilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Film;

class FilmToFilmDto
{
    public function convert(Film $film): FilmDto
    {
        $filmDto = new FilmDto();

        $filmDto->filmAfinityId = $film->filmAfinityId;
        $filmDto->title         = $film->title;
        $filmDto->originalTitle = $film->originalTitle;
        $filmDto->year          = $film->year;
        $filmDto->duration      = $film->duration;
        $filmDto->coverUrl      = $film->coverUrl;
        $filmDto->coverFile     = $film->coverFile;
        $filmDto->rating        = $film->rating;
        $filmDto->country       = $film->country;
        $filmDto->directors     = $film->directors;
        $filmDto->screenplay    = $film->screenplay;
        $filmDto->soundtrack    = $film->soundtrack;
        $filmDto->photography   = $film->photography;
        $filmDto->cast          = $film->cast;
        $filmDto->producer      = $film->producer;
        $filmDto->genres        = $film->genres;
        $filmDto->synopsis      = $film->synopsis;

        return $filmDto;
    }
}
