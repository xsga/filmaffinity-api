<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Xsga\FilmAffinityApi\Modules\Shared\Slim\ErrorHandler\ErrorHandler;
use Xsga\FilmAffinityApi\Modules\Shared\Slim\Middleware\SecurityMiddleware;

function getSlimApp(ContainerInterface $container): App
{
    // Slim app.
    AppFactory::setContainer($container);
    $app = AppFactory::create();
    $app->setBasePath((string)$container->get('env.url.path'));

    // Middlewares.
    $app->add(SecurityMiddleware::class);
    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();

    // Error handler.
    $errorMiddleware = $app->addErrorMiddleware((bool)$container->get('env.error.detail'), true, true);
    $errorMiddleware->setDefaultErrorHandler(ErrorHandler::class);

    // Routes.
    loadRoutes($app);

    return $app;
}