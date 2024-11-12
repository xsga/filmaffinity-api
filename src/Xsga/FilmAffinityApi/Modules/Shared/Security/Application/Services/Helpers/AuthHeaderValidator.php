<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Helpers;

use InvalidArgumentException;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;

final class AuthHeaderValidator
{
    public function validate(string $authHeader, SecurityTypes $type): string
    {
        $authArray = explode(' ', $authHeader);

        if (count($authArray) !== 2) {
            throw new InvalidArgumentException('Authorization header not valid');
        }

        if (strtolower($authArray[0]) !== $type->value) {
            throw new InvalidArgumentException('Authorization header not match with API security type');
        }

        return $authArray[1];
    }
}
