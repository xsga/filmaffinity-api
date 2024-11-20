<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Services;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Film;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmCastParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmCountryParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmCoverParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmDirectorsParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmGenresParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmParser;

final class GetFilmService
{
    public function __construct(
        private FilmParser $filmParser,
        private FilmCastParser $castParser,
        private FilmCoverParser $coverParser,
        private FilmDirectorsParser $directorsParser,
        private FilmGenresParser $genresParser,
        private FilmCountryParser $countryParser
    ) {
    }

    public function get(int $filmId, string $pageContent): Film
    {
        $this->initParsers($pageContent);

        return new Film(
            $filmId,
            $this->filmParser->getTitle(),
            $this->filmParser->getOriginalTitle(),
            $this->filmParser->getYear(),
            $this->filmParser->getDuration(),
            $this->filmParser->getRating(),
            $this->countryParser->getCountry(),
            $this->filmParser->getScreenplay(),
            $this->filmParser->getSoundtrack(),
            $this->filmParser->getPhotography(),
            $this->filmParser->getProducers(),
            $this->filmParser->getSynopsis(),
            $this->coverParser->getCover(),
            $this->castParser->getCast(),
            $this->directorsParser->getDirectors(),
            $this->genresParser->getGenres(),
            $this->genresParser->getGenreTopics()
        );
    }

    private function initParsers(string $pageContent): void
    {
        $this->filmParser->init($pageContent);
        $this->castParser->init($pageContent);
        $this->coverParser->init($pageContent);
        $this->directorsParser->init($pageContent);
        $this->genresParser->init($pageContent);
        $this->countryParser->init($pageContent);
    }
}
