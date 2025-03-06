<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Dto;

final class FilmDto
{
    public int $filmAfinityId = 0;
    public string $title = '';
    public string $originalTitle = '';
    public int $year = 0;
    public int $duration = 0;
    public string $coverUrl = '';
    public string $coverFile = '';
    public string $rating = '';
    public string $country = '';
    public array $directors = [];
    public string $screenplay = '';
    public string $soundtrack = '';
    public string $photography = '';
    public array $cast = [];
    public string $producer = '';
    public array $genres = [];
    public array $genreTopics = [];
    public string $synopsis = '';
}
