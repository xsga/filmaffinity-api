<?php

declare(strict_types=1);

use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Console\BackupCountriesCommand;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Console\BackupGenresCommand;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console\CreateUserCommand;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console\DeleteUserCommand;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console\DisableUserCommand;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console\EnableUserCommand;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console\GetHashedPasswordCommand;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Console\GetTokenCommand;

/** @return string[] */
function getCommands(): array
{
    return [
        CreateUserCommand::class,
        BackupGenresCommand::class,
        BackupCountriesCommand::class,
        EnableUserCommand::class,
        DisableUserCommand::class,
        DeleteUserCommand::class,
        GetHashedPasswordCommand::class,
        GetTokenCommand::class,
    ];
}
