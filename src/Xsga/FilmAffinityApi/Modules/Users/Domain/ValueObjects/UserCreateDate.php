<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects;

use DateTime;

final class UserCreateDate
{
    private readonly DateTime $createDate;

    public function __construct(DateTime $createDate)
    {
        $this->createDate = $createDate;
    }

    public function value(): DateTime
    {
        return $this->createDate;
    }
}
