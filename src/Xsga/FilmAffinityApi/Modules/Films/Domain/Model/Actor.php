<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\ActorId;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\ActorName;

class Actor
{
    private ActorId $id;
    private ActorName $name;

    public function __construct(int $id, string $name)
    {
        $this->id   = new ActorId($id);
        $this->name = new ActorName($name);
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
