<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Services;

use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SingleSearchResult;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmDirectorsParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\SimpleSearchParser;

final class GetSimpleSearchResultsService
{
    public function __construct(
        private LoggerInterface $logger,
        private SimpleSearchParser $simpleSearchParser,
        private FilmParser $filmParser,
        private FilmDirectorsParser $filmDirectorsParser
    ) {
    }

    public function get(string $pageContent): SearchResults
    {
        $this->simpleSearchParser->init($pageContent);

        $searchResults = match ($this->simpleSearchParser->isSingleResult()) {
            true => $this->getSingleResult($pageContent),
            false => $this->getMultiplesResults()
        };

        $this->logger->info('FilmAffinity search: ' . $searchResults->total() . 'results found');

        return $searchResults;
    }

    private function getSingleResult(string $pageContent): SearchResults
    {
        $this->filmParser->init($pageContent);
        $this->filmDirectorsParser->init($pageContent);

        $singleResult = new SingleSearchResult(
            $this->simpleSearchParser->getSingleResultId(),
            $this->filmParser->getTitle(),
            $this->filmParser->getYear(),
            $this->filmDirectorsParser->getDirectors()
        );

        return new SearchResults(1, [$singleResult]);
    }

    private function getMultiplesResults(): SearchResults
    {
        return new SearchResults(
            $this->simpleSearchParser->getMultiplesResultsTotal(),
            $this->simpleSearchParser->getMultiplesResults()
        );
    }
}
