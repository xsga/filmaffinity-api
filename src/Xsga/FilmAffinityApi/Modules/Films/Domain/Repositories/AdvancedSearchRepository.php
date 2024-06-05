<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\AdvancedSearch;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;

interface AdvancedSearchRepository
{
    public function get(AdvancedSearch $advancedSearch): SearchResults;
}
