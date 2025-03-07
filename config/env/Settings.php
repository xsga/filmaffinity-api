<?php

declare(strict_types=1);

use Dotenv\Dotenv;

function loadEnvironmentSettings(): void
{
    $settings = Dotenv::createMutable(getPathTo('config'));
    $settings->safeLoad();

    $settings->required('URL_PATH');
    $settings->required('ERROR_DETAIL')->isBoolean();
    $settings->required('LOG_SQL')->isBoolean();
    $settings->required('LANGUAGE')->allowedValues(['spa', 'en']);
    $settings->required('SECURITY_TYPE')->allowedValues(['basic', 'token']);
    $settings->required('JWT_LIFETIME')->isInteger();
    $settings->required('JWT_SECRET_KEY');
    $settings->required('DB_HOST');
    $settings->required('DB_USER');
    $settings->required('DB_PASSWORD');
    $settings->required('DB_SCHEMA');
    $settings->required('DB_PORT');
    $settings->required('BASE_URL');
    $settings->required('SEARCH_URL');
    $settings->required('ADV_SEARCH_URL');
    $settings->required('FILM_URL');
}
