<?php

declare(strict_types=1);

namespace Xsga\FilmAffinityApi\Modules\Shared\Api\Domain;

enum EnvironmentTypes: string
{
    case DEVELOPMENT = 'dev';
    case PRODUCTION  = 'pro';
}
