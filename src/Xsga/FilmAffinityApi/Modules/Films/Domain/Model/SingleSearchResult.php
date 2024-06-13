<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmId;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmTitle;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmYear;

class SingleSearchResult
{
    private FilmId $id;
    private FilmTitle $title;
    private FilmYear $year;

    /**
     * @var Director[]
     */
    private array $directors;

    /**
     * @param Director[] $directors
     */
    public function __construct(int $id, string $title, string $year, array $directors)
    {
        $this->id        = new FilmId($id);
        $this->title     = new FilmTitle($title);
        $this->year      = new FilmYear($year);
        $this->directors = $directors;
    }

    public function id(): int
    {
        return $this->id->value();
    }

    public function title(): string
    {
        return $this->title->value();
    }

    public function year(): string
    {
        return $this->year->value();
    }

    /**
     * @return Director[]
     */
    public function directors(): array
    {
        return $this->directors;
    }
}
