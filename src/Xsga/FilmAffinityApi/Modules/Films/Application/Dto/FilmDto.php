<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\App\Application\Dto;

class FilmDto
{
    public int $filmAfinityId = 0;
    public string $title = '';
    public string $originalTitle = '';
    public string $year = '';
    public string $duration = '';
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
    public string $synopsis = '';
}
