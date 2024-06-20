<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\DirectorId;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\DirectorName;

class Director
{
    private DirectorId $id;
    private DirectorName $name;

    public function __construct(int $id, string $name)
    {
        $this->id   = new DirectorId($id);
        $this->name = new DirectorName($name);
    }

    public function id(): int
    {
        return $this->id->value();
    }

    public function name(): string
    {
        return $this->name->value();
    }
}
