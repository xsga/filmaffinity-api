<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Security\Domain;

enum SecurityTypes: string
{
    case BASIC = 'basic';
    case TOKEN = 'bearer';
}
