<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Dto;

final class AdvancedSearchDto
{
    public string $searchText = '';
    public bool $searchTypeTitle = false;
    public bool $searchTypeDirector = false;
    public bool $searchTypeCast = false;
    public bool $searchTypeScreenplay = false;
    public bool $searchTypePhotography = false;
    public bool $searchTypeSoundtrack = false;
    public bool $searchTypeProducer = false;
    public string $searchCountryCode = '';
    public string $searchGenreCode = '';
    public int $searchYearFrom = 0;
    public int $searchYearTo = 0;
}
