<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Film;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmCastParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmCoverParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmDirectorsParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmGenresParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\FilmsRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;

final class FilmAffinityFilmsRepository implements FilmsRepository
{
    public function __construct(
        private UrlService $urlService,
        private HttpClientService $httpClientService,
        private FilmParser $filmParser,
        private FilmCastParser $castParser,
        private FilmCoverParser $coverParser,
        private FilmDirectorsParser $directorsParser,
        private FilmGenresParser $genresParser
    ) {
    }

    public function get(int $filmId): Film
    {
        $filmUrl     = $this->urlService->getFilmUrl($filmId);
        $pageContent = $this->httpClientService->getPageContent($filmUrl);

        $this->filmParser->init($pageContent);
        $this->castParser->init($pageContent);
        $this->coverParser->init($pageContent);
        $this->directorsParser->init($pageContent);
        $this->genresParser->init($pageContent);

        return $this->getFilm($filmId);
    }

    private function getFilm(int $filmId): Film
    {
        $film = new Film();

        $film->filmAfinityId = $filmId;
        $film->title         = $this->filmParser->getTitle();
        $film->originalTitle = $this->filmParser->getOriginalTitle();
        $film->year          = $this->filmParser->getYear();
        $film->duration      = $this->filmParser->getDuration();
        $film->country       = $this->filmParser->getCountry();
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
