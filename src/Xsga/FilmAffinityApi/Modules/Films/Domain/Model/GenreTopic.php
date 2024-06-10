<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Films\Domain\Model;

use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\GenreTopicId;
use Xsga\FilmAffinityApi\Modules\Films\Domain\ValueObjects\GenreTopicName;

class GenreTopic
{
    private GenreTopicId $id;
    private GenreTopicName $name;

    public function __construct(int $id, string $name)
    {
        $this->id   = new GenreTopicId($id);
        $this->name = new GenreTopicName($name);
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
