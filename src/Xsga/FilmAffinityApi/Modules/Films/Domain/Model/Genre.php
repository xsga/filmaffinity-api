<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\GenreCode;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\GenreName;

class Genre
{
    private GenreCode $code;
    private GenreName $name;

    public function __construct(string $code, string $name)
    {
        $this->code = new GenreCode($code);
        $this->name = new GenreName($name);
    }

    public function code(): string
    {
        return $this->code->value();
    }

    public function name(): string
    {
        return $this->name->value();
    }
}
