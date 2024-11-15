<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Services;

use Xsga\FilmAffinityApi\Modules\Films\Domain\Model\Genre;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\AdvancedSearchFormParser;

final class GetGenresService
{
    public function __construct(private AdvancedSearchFormParser $parser)
    {
    }

    /**
     * @return Genre[]
     */
    public function get(string $pageContent): array
    {
        $this->parser->init($pageContent);

        return $this->parser->getGenres();
    }
}
