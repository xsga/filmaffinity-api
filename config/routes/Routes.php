<?php

declare(strict_types=1);

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

function getRoutes(App $slimApp): App
{
    // Secured routes.
    $slimApp->group('', function (RouteCollectorProxy $group) {
        $group->post('/search/simple', SimpleSearchController::class);
        $group->post('/search/advanced', AdvancedSearchController::class);
        $group->get('/films/{id:[0-9]+}', GetFilmController::class);
        $group->get('/genres', GetGenresController::class);
        $group->get('/countries', GetCountriesController::class);
    })->add(SecurityMiddleware::class);

    //$slimApp->post('/users/token', GetTokenController::class)->setName('get_token');

    // Non secured rules.
    $slimApp->post('/token', GetTokenController::class)->add(TokenMiddleware::class);

    return $slimApp;
}
