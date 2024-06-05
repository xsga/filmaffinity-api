<?php

declare(strict_types=1);

function bootstrap(): void
{
    $_ENV['APP_ROOT'] = realpath(dirname(__FILE__, 5)) . DIRECTORY_SEPARATOR;

    loadEnvironmentSettings();
}