<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\Mappers;

use Xsga\FilmAffinityApi\Modules\Films\Application\Dto\AdvancedSearchDto;

final class JsonAdvancedSearchToAdvancedSearchDto
{
    public function convert(array $jsonData): AdvancedSearchDto
    {
        $advancedSearchDto = new AdvancedSearchDto();

        $advancedSearchDto->searchText            = $jsonData['text'];
        $advancedSearchDto->searchTypeTitle       = $jsonData['title'] ?? false;
        $advancedSearchDto->searchTypeDirector    = $jsonData['director'] ?? false;
        $advancedSearchDto->searchTypeCast        = $jsonData['cast'] ?? false;
        $advancedSearchDto->searchTypeScreenplay  = $jsonData['screenplay'] ?? false;
        $advancedSearchDto->searchTypePhotography = $jsonData['photography'] ?? false;
        $advancedSearchDto->searchTypeSoundtrack  = $jsonData['soundtrack'] ?? false;
        $advancedSearchDto->searchTypeProducer    = $jsonData['producer'] ?? false;
        $advancedSearchDto->searchGenre           = isset($jsonData['genre']) ? strtoupper($jsonData['genre']) : '';
        $advancedSearchDto->searchCountry         = isset($jsonData['country']) ? strtoupper($jsonData['country']) : '';
        $advancedSearchDto->searchYearFrom        = $jsonData['year_from'] ?? 0;
        $advancedSearchDto->searchYearTo          = $jsonData['year_to'] ?? 0;

        return $advancedSearchDto;
    }
}
