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
use Xsga\FilmAffinityApi\Controllers\AdvancedSearchController;
use Xsga\FilmAffinityApi\Controllers\GetCountriesController;
use Xsga\FilmAffinityApi\Controllers\GetFilmController;
use Xsga\FilmAffinityApi\Controllers\GetGenresController;
use Xsga\FilmAffinityApi\Controllers\SimpleSearchController;

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
    // Routes.
    $app->post('/search/simple', [SimpleSearchController::class, 'search']);
    $app->post('/search/advanced', [AdvancedSearchController::class, 'search']);
    $app->get('/films/{id:[0-9]+}', [GetFilmController::class, 'get']);
    $app->get('/genres', [GetGenresController::class, 'get']);
    $app->get('/countries', [GetCountriesController::class, 'get']);

    return $app;
}
