<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Search;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\SearchResults;

interface SearchRepository
{
    public function get(Search $search): SearchResults;
}
