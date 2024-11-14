<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\AdvancedSearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\AdvancedSearch;

class AdvSearchDtoToAdvSearch
{
    public function convert(AdvancedSearchDto $advSearchDto): AdvancedSearch
    {
        return new AdvancedSearch(
            $advSearchDto->searchText,
            $advSearchDto->searchTypeTitle,
            $advSearchDto->searchTypeDirector,
            $advSearchDto->searchTypeCast,
            $advSearchDto->searchTypeScreenplay,
            $advSearchDto->searchTypePhotography,
            $advSearchDto->searchTypeSoundtrack,
            $advSearchDto->searchTypeProducer,
            $advSearchDto->searchCountryCode === '' ? null : $advSearchDto->searchCountryCode,
            $advSearchDto->searchGenreCode === '' ? null : $advSearchDto->searchGenreCode,
            $advSearchDto->searchYearFrom === 0 ? null : $advSearchDto->searchYearFrom,
            $advSearchDto->searchYearTo === 0 ? null : $advSearchDto->searchYearTo
        );
    }
}
