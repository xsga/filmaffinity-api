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
 * @param Container $container   Container instance.
 * @param boolean   $errorDetail Error detail flag.
 * @param string    $urlPath     API URL path.
 *
 * @return App
 *
 * @access public
 */
function getSlimApp(Container $container, bool $errorDetail, string $urlPath): App
{
    // Adds container.
    AppFactory::setContainer($container);

    // Creates Slim app.
    $app = AppFactory::create();

    // Set URL base path.
    $app->setBasePath($urlPath);

    // Body parsing middleware.
    $app->addBodyParsingMiddleware();

    // Error middleware.
    $errMiddleware = $app->addErrorMiddleware($errorDetail, true, true);

    // Set custom error handler.
    $errMiddleware->setDefaultErrorHandler(ErrorHandler::class);

    return $app;
}
