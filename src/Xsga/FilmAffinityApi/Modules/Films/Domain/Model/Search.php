<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\SearchText;

class Search
{
    private SearchText $text;

    public function __construct(string $searchText)
    {
        $this->text = new SearchText($searchText);
    }

    public function text(): string
    {
        return $this->text->value();
    }
}
