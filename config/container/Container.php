<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Log4Php\Logger;
use Log4Php\LoggerWrapper;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Mappers\ErrorToErrorDto;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Mappers\Impl\ErrorToErrorDtoImpl;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Services\GetAllErrorsService;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Services\GetErrorService;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Services\Impl\GetAllErrorsServiceImpl;
use Xsga\FilmAffinityApi\Modules\Errors\Application\Services\Impl\GetErrorServiceImpl;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Repositories\ErrorsRepository;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Services\GetError;
use Xsga\FilmAffinityApi\Modules\Errors\Domain\Services\Impl\GetErrorImpl;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers\Mappers\ErrorDtoToErrorResponse;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Controllers\Mappers\Impl\ErrorDtoToErrorResponseImpl;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers\Impl\JsonErrorToErrorImpl;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Mappers\JsonErrorToError;
use Xsga\FilmAffinityApi\Modules\Errors\Infrastructure\Repositories\JsonErrorsRepository;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\GetSchemaService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonLoaderService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Application\Services\JsonValidatorService;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services\GetSchemaServiceImpl;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services\JsonLoaderServiceImpl;
use Xsga\FilmAffinityApi\Modules\Shared\JsonUtils\Infrastructure\Services\SwaggestJsonValidationService;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Application\Services\JWTService;
use Xsga\FilmAffinityApi\Modules\Shared\JWT\Infrastructure\Services\FirebaseJwtService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\AuthSecurityService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\BasicSecurityService;
use Xsga\FilmAffinityApi\Modules\Shared\Security\Application\Services\Impl\AuthSecurityServiceImpl;
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

return [
    // --------------------------------------------------------------------------------------------
    // CONFIGURATION.
    // --------------------------------------------------------------------------------------------

    // FOLDERS.
    'root.folder' => getPathTo(),
    'entity.folders' => [getPathTo('src#Xsga#FilmAffinityApi#Entities')],
    'schema.folder' => getPathTo('src#Xsga#FilmAffinityApi#Resources#Schemas#Api#Input'),
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

    // Application mappers.
    ErrorToErrorDto::class => DI\create(ErrorToErrorDtoImpl::class),

    // Application services.
    GetAllErrorsService::class => DI\create(GetAllErrorsServiceImpl::class)->constructor(
        DI\get(ErrorsRepository::class),
        DI\get(ErrorToErrorDto::class)
    ),
    GetErrorService::class => DI\create(GetErrorServiceImpl::class)->constructor(
        DI\get(GetError::class),
        DI\get(ErrorToErrorDto::class)
    ),

    // Domain repositories.
    ErrorsRepository::class => DI\create(JsonErrorsRepository::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(GetSchemaService::class),
        DI\get(JsonLoaderService::class),
        DI\get('errors.folder'),
        DI\get('getLanguage'),
        DI\get(JsonErrorToError::class)
    ),

    // Domain services.
    GetError::class => DI\create(GetErrorImpl::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(ErrorsRepository::class)
    ),

    // Infrastructure controller mappers.
    ErrorDtoToErrorResponse::class => DI\create(ErrorDtoToErrorResponseImpl::class),

    // Infrastructure mappers.
    JsonErrorToError::class => DI\create(JsonErrorToErrorImpl::class),

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
    AuthSecurityService::class => DI\create(AuthSecurityServiceImpl::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(GetUser::class)
    ),
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

    // SLIM middleware.
    SecurityMiddleware::class => DI\create(SecurityMiddleware::class)->constructor(
        DI\get(LoggerInterface::class),
        DI\get(BasicSecurityService::class),
        DI\get(TokenSecurityService::class),
        DI\get(AuthSecurityService::class),
        DI\get('getSecurityType')
    ),
];