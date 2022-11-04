<?php

/**
 * Routes.
 *
 * Adds API routes to Slim app.
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
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Xsga\FilmAffinityApi\Controllers\AdvancedSearchController;
use Xsga\FilmAffinityApi\Controllers\GetCountriesController;
use Xsga\FilmAffinityApi\Controllers\GetFilmController;
use Xsga\FilmAffinityApi\Controllers\GetGenresController;
use Xsga\FilmAffinityApi\Controllers\GetTokenController;
use Xsga\FilmAffinityApi\Controllers\SimpleSearchController;
use Xsga\FilmAffinityApi\Helpers\Slim\SecurityMiddleware;
use Xsga\FilmAffinityApi\Helpers\Slim\TokenMiddleware;

/**
 * Adds API routes to Slim app.
 *
 * @param App $app Slim application.
 *
 * @return App
 *
 * @access public
 */
function getRoutes(App $app): App
{
    // Secured routes.
    $app->group('', function (RouteCollectorProxy $group) {
        $group->post('/search/simple', SimpleSearchController::class);
        $group->post('/search/advanced', AdvancedSearchController::class);
        $group->get('/films/{id:[0-9]+}', GetFilmController::class);
        $group->get('/genres', GetGenresController::class);
        $group->get('/countries', GetCountriesController::class);
    })->add(SecurityMiddleware::class);

    // Non secured rules.
    $app->post('/token', GetTokenController::class)->add(TokenMiddleware::class);

    return $app;
}
