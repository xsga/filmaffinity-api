<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmId;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmTitle;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmYear;

class Film
{
    public FilmId $filmAfinityId;
    public FilmTitle $title;
    public FilmTitle $originalTitle = '';
    public FilmYear $year;
    public string $duration = '';
    public string $rating = '';
    public Country $country;
    public string $screenplay = '';
    public string $soundtrack = '';
    public string $photography = '';
    public string $producer = '';
    public string $synopsis = '';

    public Cover $cover;

    /**
     * @var Actor[]
     */
    public array $cast = [];

    /**
     * @var Director[]
     */
    public array $directors = [];

    /**
     * @var Genre[]
     */
    public array $genres = [];

    /**
     * @var GenreTopic[]
     */
    public array $genreTopics = [];
}
