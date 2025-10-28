<?php

declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;

function getDIContainer(): Container
{
    $containerPath = getPathTo('config#container');

    $builder = new ContainerBuilder();
    $builder->useAttributes(true);
    $builder->addDefinitions($containerPath . 'Container.php');

    return $builder->build();
}
