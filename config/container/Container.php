<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use GuzzleHttp\Client;
use Log4Php\Logger;
use Log4Php\LoggerWrapper;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Repositories\ErrorsRepository;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers\JsonErrorToError;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Repositories\JsonErrorsRepository;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\CountryToCountryDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Mappers\GenreToGenreDto;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\BackupCountriesService;
use Xsga\FilmAffinityApi\Modules\Films\Application\Services\BackupGenresService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\AdvancedSearchRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\CountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\FilmsRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\GenresRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Repositories\SearchRepository;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetAdvancedSearchResultsService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetCountriesService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetFilmService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetGenresService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\GetSimpleSearchResultsService;
use Xsga\FilmAffinityApi\Modules\Films\Domain\Services\UrlService;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\FilmAffinityAdvancedSearchRepository;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\FilmAffinityCountriesRepository;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\FilmAffinityFilmsRepository;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\FilmAffinityGenresRepository;
use Xsga\FilmAffinityApi\Modules\Films\Infrastructure\Repositories\FilmAffinitySearchRepository;
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
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Helpers\GetAuthHeaderToken;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\TokenSecurityService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Domain\SecurityTypes;
use Xsga\FilmAffinityApi\Modules\Shared\Slim\Middleware\SecurityMiddleware;
use Xsga\FilmAffinityApi\Modules\Users\Application\Mappers\UserToUserDto;
use Xsga\FilmAffinityApi\Modules\Users\Application\Services\GetTokenService;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Repositories\UsersRepository;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\GetUser;
use Xsga\FilmAffinityApi\Modules\Users\Domain\Services\UserLogin;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\UserEntityToUser;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Mappers\UserToNewUserEntity;
use Xsga\FilmAffinityApi\Modules\Users\Infrastructure\Repositories\DoctrineUsersRepository;

return [
    // --------------------------------------------------------------------------------------------
    // CONFIGURATION.
    // --------------------------------------------------------------------------------------------

    // FOLDERS.
    'root.folder'           => getPathTo(),
    'entity.folders'        => [
        getPathTo('src#Xsga#FilmAffinityApi#Modules#Shared#Persistence#Infrastructure#Doctrine'),
    ],
    'schema.folder'         => getPathTo('config#schemas#input'),
    'backup.folder'         => getPathTo('data#backup'),
    'entities.proxy.folder' => getPathTo('tmp#doctrine-proxies'),
    'logger.config.folder'  => getPathTo('config#logger'),
    'errors.folder'         => getPathTo('config#errors'),
    'cache.folder'          => getPathTo('tmp#cache'),

    // ENVIRONMENT.
    'env.language'       => $_ENV['LANGUAGE'],
    'env.error.detail'   => filter_var($_ENV['ERROR_DETAIL'], FILTER_VALIDATE_BOOLEAN),
    'env.log.sql'        => filter_var($_ENV['LOG_SQL'], FILTER_VALIDATE_BOOLEAN),
    'env.url.path'       => $_ENV['URL_PATH'],
    'env.jwt.secret.key' => $_ENV['JWT_SECRET_KEY'],
    'env.jwt.lifetime'   => (int)$_ENV['JWT_LIFETIME'],
    'env.security.type'  => function (): SecurityTypes {
        return match ($_ENV['SECURITY_TYPE']) {
            'basic' => SecurityTypes::BASIC,
            'token' => SecurityTypes::TOKEN
        };
    },
    'env.date.mask'     => 'd/m/Y',
    'env.datetime.mask' => 'd/m/Y H:i:s',
    'env.database.info' => [
        'driver'   => 'pdo_mysql',
        'dbname'   => $_ENV['DB_SCHEMA'],
        'user'     => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
        'host'     => $_ENV['DB_HOST'],
        'port'     => $_ENV['DB_PORT'],
        'charset'  => 'UTF8'
    ],

    // FILMAFFINITY.
    'filmaffinity.baseURL'           => $_ENV['BASE_URL'],
    'filmaffinity.searchURL'         => $_ENV['SEARCH_URL'],
    'filmaffinity.advancedSearchURL' => $_ENV['ADV_SEARCH_URL'],
    'filmaffinity.filmURL'           => $_ENV['FILM_URL'],
    'filmaffinity.getBaseURL'        => function (ContainerInterface $container): string {
        return match (strtolower($container->get('env.language'))) {
            'spa' => $container->get('filmaffinity.baseURL') . 'es/',
            'en' => $container->get('filmaffinity.baseURL') . 'us/'
        };
    },

    // --------------------------------------------------------------------------------------------
    // ENTITY MANAGER.
    // --------------------------------------------------------------------------------------------
    EntityManagerInterface::class => function (ContainerInterface $container): EntityManager {
        $isDevMode = true;

        /** @var string[] $entityPaths */
        $entityPaths = $container->get('entity.folders');
        $proxyPath   = (string)$container->get('entities.proxy.folder');

        $config = ORMSetup::createAttributeMetadataConfiguration($entityPaths, $isDevMode, $proxyPath, null);
        $config->setAutoGenerateProxyClasses($isDevMode);

        if ($container->get('env.log.sql')) {
            /** @var Middleware $loggerMiddleware */
            $loggerMiddleware = $container->get(Middleware::class);
            $config->setMiddlewares([$loggerMiddleware]);
        }

        /** @var string[] $params */
        $params = $container->get('env.database.info');

        /** @psalm-suppress MixedArgumentTypeCoercion */
        $connection = DriverManager::getConnection($params, $config);

        return new EntityManager($connection, $config);
    },

    // --------------------------------------------------------------------------------------------
    // LOGGER.
    // --------------------------------------------------------------------------------------------
    Logger::class => function (ContainerInterface $container): Logger {
        if (!Logger::isInitialized()) {
            $loggerConfigFolder = (string)$container->get('logger.config.folder');
            Logger::configure($loggerConfigFolder . 'log4php.xml');
        }
        return Logger::getRootLogger();
    },
    'logger-cli' => function (ContainerInterface $container): Logger {
        $loggerConfigFolder = (string)$container->get('logger.config.folder');
        if (!Logger::isInitialized()) {
            Logger::configure($loggerConfigFolder . 'log4php-cli.xml');
        }
        return Logger::getRootLogger();
    },
    LoggerInterface::class => function (ContainerInterface $container): LoggerWrapper {
        /** @var Logger $logger */
        $logger = match (php_sapi_name()) {
            'cli' => $container->get('logger-cli'),
            default => $container->get(Logger::class)
        };
        return new LoggerWrapper($logger);
    },

    // --------------------------------------------------------------------------------------------
    // CONSOLE.
    // --------------------------------------------------------------------------------------------
    Application::class => function (ContainerInterface $container): Application {
        $application = new Application();

        foreach (getCommands() as $command) {
            /** @var Command $commandInstance */
            $commandInstance = $container->get($command);
            $application->add($commandInstance);
        }

        return $application;
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
        DI\get('env.language'),
        DI\get(JsonErrorToError::class)
    ),

    // --------------------------------------------------------------------------------------------
    // USERS MODULE.
    // --------------------------------------------------------------------------------------------

    // Application mappers.
    UserToUserDto::class => DI\create(UserToUserDto::class)->constructor(
        DI\get('env.datetime.mask')
    ),

    // Domain repositories.
    UsersRepository::class => DI\create(DoctrineUsersRepository::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(EntityManagerInterface::class),
        DI\get(UserEntityToUser::class),
        DI\get(UserToNewUserEntity::class)
    ),

    // Application services.
    GetTokenService::class => DI\create(GetTokenService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(UserLogin::class),
        DI\get(JWTService::class),
        DI\get('env.jwt.secret.key'),
        DI\get('env.jwt.lifetime')
    ),

    // --------------------------------------------------------------------------------------------
    // FILMS MODULE.
    // --------------------------------------------------------------------------------------------

    // Application services.
    BackupGenresService::class => DI\create(BackupGenresService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(FilmAffinityGenresRepository::class),
        DI\get(GenreToGenreDto::class),
        DI\get('env.language'),
        DI\get('backup.folder')
    ),

    BackupCountriesService::class => DI\create(BackupCountriesService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(FilmAffinityCountriesRepository::class),
        DI\get(CountryToCountryDto::class),
        DI\get('env.language'),
        DI\get('backup.folder')
    ),

    // Domain services.
    UrlService::class => DI\create(UrlService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get('filmaffinity.getBaseURL'),
        DI\get('filmaffinity.filmURL'),
        DI\get('filmaffinity.searchURL'),
        DI\get('filmaffinity.advancedSearchURL')
    ),

    // Domain repositories.
    FilmsRepository::class => DI\create(FilmAffinityFilmsRepository::class)->constructor(
        DI\get(UrlService::class),
        DI\get(HttpClientService::class),
        DI\get(GetFilmService::class)
    ),
    GenresRepository::class => DI\create(FilmAffinityGenresRepository::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(UrlService::class),
        DI\get(HttpClientService::class),
        DI\get(GetGenresService::class)
    ),
    CountriesRepository::class => DI\create(FilmAffinityCountriesRepository::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(UrlService::class),
        DI\get(HttpClientService::class),
        DI\get(GetCountriesService::class)
    ),
    AdvancedSearchRepository::class => DI\create(FilmAffinityAdvancedSearchRepository::class)->constructor(
        DI\get(UrlService::class),
        DI\get(HttpClientService::class),
        DI\get(GetAdvancedSearchResultsService::class)
    ),
    SearchRepository::class => DI\create(FilmAffinitySearchRepository::class)->constructor(
        DI\get(UrlService::class),
        DI\get(HttpClientService::class),
        DI\get(GetSimpleSearchResultsService::class)
    ),
    
    // --------------------------------------------------------------------------------------------
    // SHARED MODULE.
    // --------------------------------------------------------------------------------------------

    // JSON UTILS application services.
    GetSchemaService::class => DI\create(GetSchemaServiceImpl::class)->constructor(
        DI\get(LoggerInterface::class),
        [DI\get('schema.folder')]
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
        DI\get(LoggerInterface::class)
    ),

    // HTTP CLIENT application services.
    HttpClientService::class => DI\create(GuzzleHttpClientService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(Client::class)
    ),

    // SLIM middleware.
    SecurityMiddleware::class => DI\create(SecurityMiddleware::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(BasicSecurityService::class),
        DI\get(TokenSecurityService::class),
        DI\get('env.security.type')
    ),

    // SECURITY services.
    TokenSecurityService::class => DI\create(TokenSecurityService::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(JWTService::class),
        DI\get(GetUser::class),
        DI\get(GetAuthHeaderToken::class),
        DI\get('env.jwt.secret.key')
    ),
];
