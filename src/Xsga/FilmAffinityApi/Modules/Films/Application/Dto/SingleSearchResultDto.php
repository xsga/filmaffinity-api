<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Dto;

class SingleSearchResultDto
{
    public int $id = 0;
    public string $title = '';
    public int $year = 0;
    public array $directors;
}
