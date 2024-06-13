<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

class SingleSearchResult
{
    public int $id = 0;
    public string $title = '';
    public string $year = '';
    
    /**
     * @return Director[]
     */
    public array $directors;
}
