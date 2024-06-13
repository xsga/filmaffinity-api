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
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmId;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmTitle;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmYear;

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
        $this->filmParser->init($pageContent);
        $this->castParser->init($pageContent);
        $this->coverParser->init($pageContent);
        $this->directorsParser->init($pageContent);
        $this->genresParser->init($pageContent);
        $this->countryParser->init($pageContent);

        return $this->getFilm($filmId);
    }

    private function getFilm(int $filmId): Film
    {
        $film = new Film();

        $film->filmAfinityId = new FilmId($filmId);
        $film->title         = new FilmTitle($this->filmParser->getTitle());
        $film->originalTitle = new FilmTitle($this->filmParser->getOriginalTitle());
        $film->year          = new FilmYear($this->filmParser->getYear());
        $film->duration      = $this->filmParser->getDuration();
        $film->country       = $this->countryParser->getCountry();
        $film->directors     = $this->directorsParser->getDirectors();
        $film->screenplay    = $this->filmParser->getScreenplay();
        $film->soundtrack    = $this->filmParser->getSoundtrack();
        $film->photography   = $this->filmParser->getPhotography();
        $film->cast          = $this->castParser->getCast();
        $film->producer      = $this->filmParser->getProducers();
        $film->genres        = $this->genresParser->getGenres();
        $film->genreTopics   = $this->genresParser->getGenreTopics();
        $film->rating        = $this->filmParser->getRating();
        $film->synopsis      = $this->filmParser->getSynopsis();
        $film->cover         = $this->coverParser->getCover();

        return $film;
    }
}
