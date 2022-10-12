<?php

/**
 * Slim app.
 *
 * Gets Slim application.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Import dependencies.
 */
use DI\Container;
use Slim\App;
use Slim\Factory\AppFactory;
use Xsga\FilmAffinityApi\Helpers\Slim\ErrorHandler;

/**
 * Gets Slim application.
 *
 * @param Container $container Container instance.
 *
 * @return App
 *
 * @access public
 */
function getSlimApp(Container $container): App
{
    // Adds container.
    AppFactory::setContainer($container);

    // Creates Slim app.
    $app = AppFactory::create();

    // Set URL base path.
    $app->setBasePath($_ENV['URL_PATH']);

    // Routing middleware.
    $app->addRoutingMiddleware();

    // Body parsing middleware.
    $app->addBodyParsingMiddleware();

    // Error middleware.
    $errMiddleware = $app->addErrorMiddleware(filter_var($_ENV['ERROR_DETAIL'], FILTER_VALIDATE_BOOLEAN), true, true);

    // Set custom error handler.
    $errMiddleware->setDefaultErrorHandler(ErrorHandler::class);

    return $app;
}
