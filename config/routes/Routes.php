<?php

declare(strict_types=1);

use Slim\App;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\AdvancedSearchController;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\GetAllCountriesController;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\GetAllGenresController;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\GetFilmByIdController;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Controllers\SimpleSearchController;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Controllers\GetTokenController;

function getRoutes(App $slimApp): App
{
    $slimApp->post('/searches/simple', SimpleSearchController::class)->setName('simple_search');
    $slimApp->post('/searches/advanced', AdvancedSearchController::class)->setName('advanced_search');
    $slimApp->get('/films/{id:[0-9]+}', GetFilmByIdController::class)->setName('get_film_by_id');
    $slimApp->get('/genres', GetAllGenresController::class)->setName('get_all_genres');
    $slimApp->get('/countries', GetAllCountriesController::class)->setName('get_all_countries');
    $slimApp->post('/users/token', GetTokenController::class)->setName('get_token');

    return $slimApp;
}
