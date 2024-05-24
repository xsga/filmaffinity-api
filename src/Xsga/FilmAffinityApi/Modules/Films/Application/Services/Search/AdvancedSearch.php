<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Business\Search;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\App\Application\Dto\AdvSearchDto;

final class AdvancedSearch
{
    public function __construct(
        private LoggerInterface $logger,
        private string $searchUrl,
        private Extractor $extractor,
        private AdvancedSearchParser $parser,
        private Genres $genres,
        private Countries $countries
    ) {
    }

    public function search(AdvSearchDto $advSearchDto): SearchResultsDto
    {
        $this->validations($advSearchDto);

        $pageContent = $this->extractor->getData($this->getUrl($advSearchDto));

        $this->parser->init($pageContent);

        $out = $this->parser->getAdvSearchResultsDto();

        return $out;
    }

    private function validations(AdvSearchDto $advSearchDto): void
    {
        if (strlen($advSearchDto->searchText) < 3) {
            $errorMsg = 'Search text lenght not valid';
            $this->logger->error($errorMsg);
            throw new InvalidLengthException($errorMsg, 1008);
        }

        if (!empty($advSearchDto->searchGenre)) {
            $genre = $this->genres->get($advSearchDto->searchGenre);

            if ($genre->code === '') {
                $errorMsg = 'Genre code not valid';
                $this->logger->error($errorMsg);
                throw new GenreNotFoundException($errorMsg, 1009);
            }
        }

        if (!empty($advSearchDto->searchCountry)) {
            $country = $this->countries->get($advSearchDto->searchCountry);

            if ($country->code === '') {
                $errorMsg = 'Country code not valid';
                $this->logger->error($errorMsg);
                throw new CountryNotFoundException($errorMsg, 1010);
            }
        }
    }

    private function getUrl(AdvSearchDto $advSearchDto): string
    {
        $searchType  = '';
        $searchType .= $advSearchDto->searchTypeTitle ? '&stype[]=title' : '';
        $searchType .= $advSearchDto->searchTypeDirector ? '&stype[]=director' : '';
        $searchType .= $advSearchDto->searchTypeCast ? '&stype[]=cast' : '';
        $searchType .= $advSearchDto->searchTypeSoundtrack ? '&stype[]=music' : '';
        $searchType .= $advSearchDto->searchTypeScreenplay ? '&stype[]=script' : '';
        $searchType .= $advSearchDto->searchTypePhotography ? '&stype[]=photo' : '';
        $searchType .= $advSearchDto->searchTypeProducer ? '&stype[]=producer' : '';

        if ($searchType === '') {
            $this->logger->warning('No search type found. Set the default search type: title');
            $searchType = '&stype[]=title';
        }

        $url = $this->searchUrl;
        $url = str_replace('{1}', $this->prepareSearchText($advSearchDto->searchText), $url);
        $url = str_replace('{2}', $searchType, $url);
        $url = str_replace('{3}', $advSearchDto->searchCountry, $url);
        $url = str_replace('{4}', $advSearchDto->searchGenre, $url);
        $url = str_replace('{5}', $advSearchDto->searchYearFrom === 0 ? '' : $advSearchDto->searchYearFrom, $url);
        $url = str_replace('{6}', $advSearchDto->searchYearTo === 0 ? '' : $advSearchDto->searchYearTo, $url);

        $this->logger->debug('Advanced Search URL: ' . $url);

        return $url;
    }

    private function prepareSearchText(string $searchText): string
    {
        $searchText = trim($searchText);
        $searchText = str_replace(' ', '+', $searchText);

        return $searchText;
    }
}
