<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

class Film
{
    public int $filmAfinityId = 0;
    public string $title = '';
    public string $originalTitle = '';
    public string $year = '';
    public string $duration = '';
    public string $coverUrl = '';
    public string $coverFile = '';
    public string $rating = '';
    public Country $country;
    public string $screenplay = '';
    public string $soundtrack = '';
    public string $photography = '';
    public string $producer = '';
    public string $synopsis = '';

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
