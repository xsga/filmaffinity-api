<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Users\Domain\ValueObjects;

use DateTime;

final class UserUpdateDate
{
    private readonly DateTime $updateDate;

    public function __construct(DateTime $updateDate)
    {
        $this->updateDate = $updateDate;
    }

    public function value(): DateTime
    {
        return $this->updateDate;
    }
}
