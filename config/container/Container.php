<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use GuzzleHttp\Client;
use Log4Php\Logger;
use Log4Php\LoggerWrapper;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Repositories\ErrorsRepository;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers\JsonErrorToError;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Repositories\JsonErrorsRepository;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\FilmToFilmDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\SearchResultsToSearchResultsDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\AdvancedSearchService;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\GetFilmByIdService;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\SimpleSearchService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\AdvancedSearchParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\FilmParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Parsers\SimpleSearchParser;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\CountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\GenresRepository;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers\JsonCountryToCountry;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Mappers\JsonGenreToGenre;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\JsonCountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\JsonGenresRepository;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Application\Services\HttpClientService;
use Xsga\FilmAffinityApi\Modules\Shared\HttpClient\Infrastructure\Services\GuzzleHttpClientService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonLoaderService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonValidatorService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services\GetSchemaServiceImpl;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services\JsonLoaderServiceImpl;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services\SwaggestJsonValidationService;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services\JWTService;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Infrastructure\Services\FirebaseJwtService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\BasicSecurityService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Impl\BasicSecurityServiceImpl;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Impl\Helpers\AuthHeaderValidator;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Impl\TokenSecurityServiceImpl;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\TokenSecurityService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;
use Xsga\FilmAffinityApi\Modules\Shared\Slim\Middleware\SecurityMiddleware;
use Xsga\FilmAffinityApi\Modules\Users\Application\Mappers\UserToUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\UserLogin;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\UserEntityToUser;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\UserToNewUserEntity;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Repositories\DoctrineUsersRepository;

use function DI\create;

return [
    // --------------------------------------------------------------------------------------------
    // CONFIGURATION.
    // --------------------------------------------------------------------------------------------

    // FOLDERS.
    'root.folder' => getPathTo(),
    'entity.folders' => [getPathTo('src#Xsga#FilmAffinityApi#Entities')],
    'schema.folder' => getPathTo('config#schemas#input'),
    'resources.folder' => getPathTo('src#Xsga#FilmAffinityApi#Resources'),
    'entities.proxy.folder' => getPathTo('tmp#doctrine-proxies'),
    'logger.config.folder' => getPathTo('config#logger'),
    'errors.folder' => getPathTo('config#errors'),
    'cache.folder' => getPathTo('tmp#cache'),

    // ENVIRONMENT.
    'getLanguage' => $_ENV['LANGUAGE'],
    'getEnvironment' => $_ENV['ENVIRONMENT'],
    'getErrorDetail' => filter_var($_ENV['ERROR_DETAIL'], FILTER_VALIDATE_BOOLEAN),
    'getUrlPath' => $_ENV['URL_PATH'],
    'getJwtSecretKey' => $_ENV['JWT_SECRET_KEY'],
    'getJwtLifetime' => (int)$_ENV['JWT_LIFETIME'],
    'getSecurityType' => function () {
        return match ($_ENV['SECURITY_TYPE']) {
            'basic' => SecurityTypes::BASIC,
            'token' => SecurityTypes::TOKEN
        };
    },
    //'getDateMask' => $_ENV['DATE_MASK'],
    //'getDateTimeMask' => $_ENV['DATETIME_MASK'],
    'getDateMask' => 'd/m/Y',
    'getDateTimeMask' => 'd/m/Y H:i:s',
    'database.info' => [
        'driver'   => 'pdo_mysql',
        'dbname'   => $_ENV['DB_SCHEMA'],
        'user'     => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'host'     => $_ENV['DB_HOST'],
        'port'     => $_ENV['DB_PORT'],
        'charset'  => 'UTF8'
    ],

    // FILMAFFINITY.
    'filmaffinity.baseURL' => $_ENV['BASE_URL'],
    'filmaffinity.searchURL' => $_ENV['SEARCH_URL'],
    'filmaffinity.advancedSearchURL' => $_ENV['ADV_SEARCH_URL'],
    'filmaffinity.filmURL' => $_ENV['FILM_URL'],
    'filmaffinity.getBaseURL' => function (ContainerInterface $container): string {
        return match (strtolower($container->get('getLanguage'))) {
            'spa' => $container->get('filmaffinity.baseURL') . 'es/',
            'en' => $container->get('filmaffinity.baseURL') . 'us/'
        };
    },

    // --------------------------------------------------------------------------------------------
    // ENTITY MANAGER.
    // --------------------------------------------------------------------------------------------
    EntityManagerInterface::class => function (ContainerInterface $container) {
        $isDevMode = match ($container->get('getEnvironment')) {
            'dev' => true,
            'pro' => false,
            default => true
        };

        $entityPaths = $container->get('entity.folders');
        $proxyPath   = $container->get('entities.proxy.folder');
        $connection  = DriverManager::getConnection($container->get('database.info'));

        $config = ORMSetup::createAttributeMetadataConfiguration($entityPaths, $isDevMode, $proxyPath, null);
        $config->setAutoGenerateProxyClasses($isDevMode);

        return new EntityManager($connection, $config);
    },

    // --------------------------------------------------------------------------------------------
    // LOGGER.
    // --------------------------------------------------------------------------------------------
    Logger::class => function (ContainerInterface $container) {
        if (!Logger::isInitialized()) {
            Logger::configure($container->get('logger.config.folder') . 'log4php.xml');
        }
        return Logger::getRootLogger();
    },
    LoggerInterface::class => function (ContainerInterface $container): LoggerInterface {
        $logger = $container->get(Logger::class);
        return new LoggerWrapper($logger);
    },

    // --------------------------------------------------------------------------------------------
    // ERROR MODULE.
    // --------------------------------------------------------------------------------------------

    // Domain repositories.
    ErrorsRepository::class => DI\create(JsonErrorsRepository::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(GetSchemaService::class),
        DI\get(JsonLoaderService::class),
        DI\get('errors.folder'),
        DI\get('getLanguage'),
        DI\get(JsonErrorToError::class)
    ),

    // --------------------------------------------------------------------------------------------
    // USERS MODULE.
    // --------------------------------------------------------------------------------------------

    UserToUserDto::class => DI\create(UserToUserDto::class)->constructor(
        DI\get('getDateTimeMask')
    ),

    // Domain repositories.
    UsersRepository::class => DI\create(DoctrineUsersRepository::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(EntityManagerInterface::class),
        DI\get(UserEntityToUser::class),
        DI\get(UserToNewUserEntity::class)
    ),

    // --------------------------------------------------------------------------------------------
    // FILMS MODULE.
    // --------------------------------------------------------------------------------------------

    // Application services.
    GetFilmByIdService::class => DI\create(GetFilmByIdService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get('filmaffinity.filmURL'),
        DI\get(HttpClientService::class),
        DI\get(FilmParser::class),
        DI\get(FilmToFilmDto::class)
    ),

    SimpleSearchService::class => DI\create(SimpleSearchService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get('filmaffinity.searchURL'),
        DI\get(HttpClientService::class),
        DI\get(SimpleSearchParser::class),
        DI\get(SearchResultsToSearchResultsDto::class)
    ),

    AdvancedSearchService::class => DI\create(AdvancedSearchService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get('filmaffinity.advancedSearchURL'),
        DI\get(HttpClientService::class),
        DI\get(AdvancedSearchParser::class),
        DI\get(GenresRepository::class),
        DI\get(CountriesRepository::class),
        DI\get(SearchResultsToSearchResultsDto::class)
    ),

    // Domain repositories.
    GenresRepository::class => DI\create(JsonGenresRepository::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(GetSchemaService::class),
        DI\get(JsonLoaderService::class),
        DI\get('resources.folder'),
        DI\get('getLanguage'),
        DI\get(JsonGenreToGenre::class)
    ),

    CountriesRepository::class => DI\create(JsonCountriesRepository::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(GetSchemaService::class),
        DI\get(JsonLoaderService::class),
        DI\get('resources.folder'),
        DI\get('getLanguage'),
        DI\get(JsonCountryToCountry::class)
    ),

    // --------------------------------------------------------------------------------------------
    // SHARED MODULE.
    // --------------------------------------------------------------------------------------------

    // JSON UTILS application services.
    GetSchemaService::class => DI\create(GetSchemaServiceImpl::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get('schema.folder')
    ),
    JsonLoaderService::class => DI\create(JsonLoaderServiceImpl::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(JsonValidatorService::class)
    ),
    JsonValidatorService::class => DI\create(SwaggestJsonValidationService::class)->constructor(
        DI\get(LoggerInterface::class)
    ),

    // JWT application services.
    JWTService::class => DI\create(FirebaseJwtService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get('getJwtSecretKey'),
        DI\get('getJwtLifetime')
    ),

    // SECURITY application services.
    BasicSecurityService::class => DI\create(BasicSecurityServiceImpl::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(UserLogin::class),
        DI\get(AuthHeaderValidator::class)
    ),
    TokenSecurityService::class => DI\create(TokenSecurityServiceImpl::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(JWTService::class),
        DI\get(GetUser::class),
        DI\get(AuthHeaderValidator::class)
    ),

    // HTTP CLIENT application services.
    HttpClientService::class => DI\create(GuzzleHttpClientService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(Client::class),
        DI\get('filmaffinity.getBaseURL')
    ),

    // SLIM middleware.
    SecurityMiddleware::class => DI\create(SecurityMiddleware::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(BasicSecurityService::class),
        DI\get(TokenSecurityService::class),
        DI\get('getSecurityType')
    ),
];
