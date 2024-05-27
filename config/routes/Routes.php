<?php

declare(strict_types=1);

use Slim\App;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\AdvancedSearchController;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\GetFilmByIdController;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\SimpleSearchController;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Controllers\GetTokenController;

function getRoutes(App $slimApp): App
{
    /*
    $slimApp->get('/genres', GetGenresController::class)->setName('get_genres');
    $slimApp->get('/countries', GetCountriesController::class)->setName('get_countries');
    */

    $slimApp->get('/films/{id:[0-9]+}', GetFilmByIdController::class)->setName('get_film_by_id');
    $slimApp->post('/users/token', GetTokenController::class)->setName('get_token');
    $slimApp->post('/searches/simple', SimpleSearchController::class)->setName('simple_search');
    $slimApp->post('/searches/advanced', AdvancedSearchController::class)->setName('advanced_search');

    return $slimApp;
}
