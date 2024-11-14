<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\AdvancedSearchDto;

final class JsonAdvancedSearchToAdvancedSearchDto
{
    public function convert(array $jsonData): AdvancedSearchDto
    {
        $advancedSearchDto = new AdvancedSearchDto();

        $advancedSearchDto->searchText            = $jsonData['text'];
        $advancedSearchDto->searchTypeTitle       = $jsonData['search_in_title'];
        $advancedSearchDto->searchTypeDirector    = $jsonData['search_in_director'];
        $advancedSearchDto->searchTypeCast        = $jsonData['search_in_cast'];
        $advancedSearchDto->searchTypeScreenplay  = $jsonData['search_in_screenplay'];
        $advancedSearchDto->searchTypePhotography = $jsonData['search_in_photography'];
        $advancedSearchDto->searchTypeSoundtrack  = $jsonData['search_in_soundtrack'];
        $advancedSearchDto->searchTypeProducer    = $jsonData['search_in_producer'];
        $advancedSearchDto->searchGenreCode       = strtoupper($jsonData['genre']);
        $advancedSearchDto->searchCountryCode     = strtoupper($jsonData['country']);
        $advancedSearchDto->searchYearFrom        = $jsonData['year_from'] ?? 0;
        $advancedSearchDto->searchYearTo          = $jsonData['year_to'] ?? 0;

        return $advancedSearchDto;
    }
}
