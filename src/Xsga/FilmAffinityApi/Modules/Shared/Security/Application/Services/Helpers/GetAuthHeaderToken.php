<?php

declare(strict_types=1);

namespace Xsga\BlackBirdPhp\Modules\Shared\Security\Application\Services\Helpers;

use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\Exceptions\InvalidAuthHeaderException;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;

final class GetAuthHeaderToken
{
    public function get(string $authHeader, SecurityTypes $type): string
    {
        $authArray = explode(' ', $authHeader);

        if (count($authArray) !== 2) {
            throw new InvalidAuthHeaderException('HTTP authorization header not valid');
        }

        if (strtolower($authArray[0]) !== $type->value) {
            throw new InvalidAuthHeaderException('HTTP authorization header not match with API security type');
        }

        return $authArray[1];
    }
}
