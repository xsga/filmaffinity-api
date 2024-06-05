<?php

declare(strict_types=1);

function getPathTo(string $pathItems = ''): string
{
    $path      = $_ENV['APP_ROOT'];
    $pathItems = explode('#', $pathItems);

    foreach ($pathItems as $item) {
        $path .= $item . DIRECTORY_SEPARATOR;
    }

    return $path;
}
