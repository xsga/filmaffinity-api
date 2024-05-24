<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Controllers\GetTokenController;

function getRoutes(App $slimApp): App
{
    /*
    $slimApp->group('', function (RouteCollectorProxy $group) {
        $group->post('/search/simple', SimpleSearchController::class);
        $group->post('/search/advanced', AdvancedSearchController::class);
        $group->get('/films/{id:[0-9]+}', GetFilmController::class);
        $group->get('/genres', GetGenresController::class);
        $group->get('/countries', GetCountriesController::class);
    })->add(SecurityMiddleware::class);
    */

    $slimApp->post('/users/token', GetTokenController::class)->setName('get_token');

    return $slimApp;
}
