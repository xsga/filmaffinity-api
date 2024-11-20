<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Services;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\AdvancedSearchParser;

final class GetAdvancedSearchResultsService
{
    public function __construct(private AdvancedSearchParser $parser)
    {
    }

    public function get(string $pageContent): SearchResults
    {
        $this->parser->init($pageContent);

        return $this->parser->getAdvSearchResults();
    }
}
