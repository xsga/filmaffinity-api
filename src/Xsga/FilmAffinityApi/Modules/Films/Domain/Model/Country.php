<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\CountryCode;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\CountryName;

class Country
{
    private CountryCode $code;
    private CountryName $name;

    public function __construct(string $code, string $name)
    {
        $this->code = new CountryCode($code);
        $this->name = new CountryName($name);
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
