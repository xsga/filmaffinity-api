<?php

/**
 * Container.
 *
 * Gets DI Container.
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
use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Log4Php\Logger;
use Log4Php\LoggerWrapper;
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Business\Countries\Countries;
use Xsga\FilmAffinityApi\Business\Extractor\Extractor;
use Xsga\FilmAffinityApi\Business\Films\LoadFilm;
use Xsga\FilmAffinityApi\Business\Genres\Genres;
use Xsga\FilmAffinityApi\Business\Parser\AdvancedSearchParser;
use Xsga\FilmAffinityApi\Business\Parser\FilmParser;
use Xsga\FilmAffinityApi\Business\Parser\SimpleSearchParser;
use Xsga\FilmAffinityApi\Business\Search\AdvancedSearch;
use Xsga\FilmAffinityApi\Business\Search\SimpleSearch;
use Xsga\FilmAffinityApi\Helpers\Errors\Errors;
use Xsga\FilmAffinityApi\Helpers\Errors\ErrorsInterface;
use Xsga\FilmAffinityApi\Helpers\JsonValidator\JsonValidator;
use Xsga\FilmAffinityApi\Helpers\JsonValidator\JsonValidatorInterface;
use Xsga\FilmAffinityApi\Helpers\Schema\Schema;
use Xsga\FilmAffinityApi\Helpers\Schema\SchemaInterface;

/**
 * Get DI Container.
 *
 * @return Container
 *
 * @access public
 */
function getContainer(): Container
{
    // Container definitions.
    $containerDef = [
        Logger::class => DI\factory(
            function () {
                if (!Logger::isInitialized()) {
                    $pathTo = DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'log4php.xml';
                    $path   = realpath(dirname(__FILE__, 5) . $pathTo);
                    Logger::configure($path);
                }//end if

                return Logger::getRootLogger();
            }
        ),
        LoggerInterface::class => DI\create(LoggerWrapper::class)->constructor(
            DI\get(Logger::class)
        ),
        ErrorsInterface::class => DI\create(Errors::class)->constructor(
            DI\get(LoggerInterface::class),
            $_ENV['LANGUAGE']
        ),
        SchemaInterface::class => DI\create(Schema::class)->constructor(
            DI\get(LoggerInterface::class)
        ),
        JsonValidatorInterface::class => DI\create(JsonValidator::class)->constructor(
            DI\get(LoggerInterface::class)
        ),
        Countries::class => DI\create(Countries::class)->constructor(
            DI\get(LoggerInterface::class),
            $_ENV['LANGUAGE']
        ),
        Genres::class => DI\create(Genres::class)->constructor(
            DI\get(LoggerInterface::class),
            $_ENV['LANGUAGE']
        ),
        Extractor::class => DI\create(Extractor::class)->constructor(
            DI\get(LoggerInterface::class),
            $_ENV['LANGUAGE'],
            $_ENV['BASE_URL'],
            DI\get(Client::class)
        ),
        LoadFilm::class => DI\create(LoadFilm::class)->constructor(
            DI\get(LoggerInterface::class),
            $_ENV['FILM_URL'],
            DI\get(Extractor::class),
            DI\get(FilmParser::class)
        ),
        AdvancedSearch::class => DI\create(AdvancedSearch::class)->constructor(
            DI\get(LoggerInterface::class),
            $_ENV['ADV_SEARCH_URL'],
            DI\get(Extractor::class),
            DI\get(AdvancedSearchParser::class),
            DI\get(Genres::class),
            DI\get(Countries::class)
        ),
        SimpleSearch::class => DI\create(SimpleSearch::class)->constructor(
            DI\get(LoggerInterface::class),
            $_ENV['SEARCH_URL'],
            DI\get(Extractor::class),
            DI\get(SimpleSearchParser::class)
        ),
        Client::class => DI\create(Client::class)->constructor(
            ['http_errors' => false]
        )
    ];

    $builder = new ContainerBuilder();

    $builder->useAnnotations(true);
    $builder->addDefinitions($containerDef);

    return $builder->build();
}
