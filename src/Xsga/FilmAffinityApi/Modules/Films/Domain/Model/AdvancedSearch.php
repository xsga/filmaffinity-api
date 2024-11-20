<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\CountryCode;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\FilmYear;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\GenreCode;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\SearchText;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\SearchType;

final class AdvancedSearch
{
    private SearchText $text;
    private SearchType $typeTitle;
    private SearchType $typeDirector;
    private SearchType $typeCast;
    private SearchType $typeScreenplay;
    private SearchType $typePhotography;
    private SearchType $typeSoundtrack;
    private SearchType $typeProducer;
    private ?CountryCode $countryCode;
    private ?GenreCode $genreCode;
    private ?FilmYear $yearFrom;
    private ?FilmYear $yearTo;

    public function __construct(
        string $text,
        bool $typeTitle,
        bool $typeDirector,
        bool $typeCast,
        bool $typeScreenplay,
        bool $typePhotography,
        bool $typeSoundtrack,
        bool $typeProducer,
        ?string $countryCode,
        ?string $genreCode,
        ?int $yearFrom,
        ?int $yearTo
    ) {
        $this->text            = new SearchText($text);
        $this->typeTitle       = new SearchType($typeTitle);
        $this->typeDirector    = new SearchType($typeDirector);
        $this->typeCast        = new SearchType($typeCast);
        $this->typeScreenplay  = new SearchType($typeScreenplay);
        $this->typePhotography = new SearchType($typePhotography);
        $this->typeSoundtrack  = new SearchType($typeSoundtrack);
        $this->typeProducer    = new SearchType($typeProducer);
        $this->countryCode     = isset($countryCode) ? new CountryCode($countryCode) : null;
        $this->genreCode       = isset($genreCode) ? new GenreCode($genreCode) : null;
        $this->yearFrom        = isset($yearFrom) ? new FilmYear($yearFrom) : null;
        $this->yearTo          = isset($yearTo) ? new FilmYear($yearTo) : null;
    }

    public function text(): string
    {
        return $this->text->value();
    }

    public function typeTitle(): bool
    {
        return $this->typeTitle->value();
    }

    public function typeDirector(): bool
    {
        return $this->typeDirector->value();
    }

    public function typeCast(): bool
    {
        return $this->typeCast->value();
    }

    public function typeScreenplay(): bool
    {
        return $this->typeScreenplay->value();
    }

    public function typePhotography(): bool
    {
        return $this->typePhotography->value();
    }

    public function typeSoundtrack(): bool
    {
        return $this->typeSoundtrack->value();
    }

    public function typeProducer(): bool
    {
        return $this->typeProducer->value();
    }

    public function countryCode(): string
    {
        return isset($this->countryCode) ? $this->countryCode->value() : '';
    }

    public function genreCode(): string
    {
        return isset($this->genreCode) ? $this->genreCode->value() : '';
    }

    public function yearFrom(): int
    {
        return isset($this->yearFrom) ? $this->yearFrom->value() : 0;
    }

    public function yearTo(): int
    {
        return isset($this->yearTo) ? $this->yearTo->value() : 0;
    }
}
