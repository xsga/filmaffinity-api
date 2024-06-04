<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Application\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\AdvancedSearchDto;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\AdvancedSearch;

class AdvSearchDtoToAdvSearch
{
    public function convert(AdvancedSearchDto $advSearchDto): AdvancedSearch
    {
        $advSearch = new AdvancedSearch();

        $advSearch->searchText            = $advSearchDto->searchText;
        $advSearch->searchTypeTitle       = $advSearchDto->searchTypeTitle;
        $advSearch->searchTypeDirector    = $advSearchDto->searchTypeDirector;
        $advSearch->searchTypeCast        = $advSearchDto->searchTypeCast;
        $advSearch->searchTypeScreenplay  = $advSearchDto->searchTypeScreenplay;
        $advSearch->searchTypePhotography = $advSearchDto->searchTypePhotography;
        $advSearch->searchTypeSoundtrack  = $advSearchDto->searchTypeSoundtrack;
        $advSearch->searchTypeProducer    = $advSearchDto->searchTypeProducer;
        $advSearch->searchCountry         = $advSearchDto->searchCountry;
        $advSearch->searchGenre           = $advSearchDto->searchGenre;
        $advSearch->searchYearFrom        = $advSearchDto->searchYearFrom;
        $advSearch->searchYearTo          = $advSearchDto->searchYearTo;

        return $advSearch;
    }
}
