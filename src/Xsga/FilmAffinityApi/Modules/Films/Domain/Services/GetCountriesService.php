<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Services;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Country;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\AdvancedSearchFormParser;

final class GetCountriesService
{
    public function __construct(private AdvancedSearchFormParser $parser)
    {
    }

    /**
     * @return Country[]
     */
    public function get(string $pageContent): array
    {
        $this->parser->init($pageContent);

        return $this->parser->getCountries();
    }
}
