<?php

declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;

function getDIContainer(): Container
{
    $applicationDef = getPathTo('config#container') . 'Container.php';

    $builder = new ContainerBuilder();
    $builder->useAttributes(true);
    $builder->addDefinitions($applicationDef);

    return $builder->build();
}
