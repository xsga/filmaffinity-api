<?php

namespace Log4Php\Configurators;

use Log4Php\LoggerConfigurator;
use Log4Php\LoggerHierarchy;
use Log4Php\LoggerException;
use Log4Php\LoggerLevel;
use Log4Php\LoggerAppender;
use Log4Php\LoggerFilter;
use Log4Php\Logger;
use Log4Php\LoggerLayout;
use Log4Php\Helpers\LoggerOptionConverter;

class LoggerConfiguratorDefault implements LoggerConfigurator
{
    public const string FORMAT_XML = 'xml';
    public const string FORMAT_PHP = 'php';
    public const string FORMAT_INI = 'ini';

    private array $adapters = [
        self::FORMAT_XML => 'LoggerConfigurationAdapterXML',
        self::FORMAT_INI => 'LoggerConfigurationAdapterINI',
        self::FORMAT_PHP => 'LoggerConfigurationAdapterPHP'
    ];

    private static array $defaultConfiguration = [
        'threshold'  => 'ALL',
        'rootLogger' => [
            'level'     => 'DEBUG',
            'appenders' => ['default']
        ],
        'appenders'  => [
            'default' => [
                'class' => 'LoggerAppenderEcho'
            ]
        ]
    ];

    private array $appenders = [];

    public function configure(LoggerHierarchy $hierarchy, string|array|null $input = null): void
    {
        $config = $this->parse($input);
        $this->doConfigure($hierarchy, $config);
    }

    public function parse(string|array $input): array
    {
        if (empty($input)) {
            return static::$defaultConfiguration;
        }

        if (is_array($input)) {
            return $input;
        }

        try {
            $config = $this->parseFile($input);
        } catch (LoggerException $exception) {
            $this->warn('Configuration failed.' . $exception->getMessage() . 'Using default configuration.');
            $config = static::$defaultConfiguration;
        }

        return $config;
    }

    public static function getDefaultConfiguration(): array
    {
        return static::$defaultConfiguration;
    }

    private function parseFile(string $url): array
    {
        if (!file_exists($url)) {
            throw new LoggerException("File not found at [$url].");
        }

        $namespace = 'Log4Php\\Configurators\\';

        $type         = $this->getConfigType($url);
        $adapterClass = $namespace . $this->adapters[$type];
        $adapter      = new $adapterClass();

        return $adapter->convert($url);
    }

    private function getConfigType(string $url): string
    {
        $info = pathinfo($url);
        $ext  = strtolower($info['extension']);

        $format = match ($ext) {
            'xml' => static::FORMAT_XML,
            'ini' => static::FORMAT_INI,
            'properties' => static::FORMAT_INI,
            'php' => static::FORMAT_PHP,
            default => ''
        };

        if ($format === '') {
            throw new LoggerException("Unsupported configuration file extension: $ext");
        }

        return $format;
    }

    private function doConfigure(LoggerHierarchy $hierarchy, array $config): void
    {
        $this->doConfigThreshold($hierarchy, $config);
        $this->doConfigAppenders($config);
        $this->doConfigRootLogger($hierarchy, $config);
        $this->doConfigLoggers($hierarchy, $config);
        $this->doConfigRenderers($hierarchy, $config);
        $this->doConfigDefaultRenderer($hierarchy, $config);
    }

    private function doConfigThreshold(LoggerHierarchy $hierarchy, array $config): void
    {
        if (isset($config['threshold'])) {
            $threshold = LoggerLevel::toLevel($config['threshold']);

            if (isset($threshold)) {
                $hierarchy->setThreshold($threshold);
                return;
            }
            $log  = 'Invalid threshold value [' . $config['threshold'] . ']';
            $log .= ' specified. Ignoring threshold definition.';
            $this->warn($log);
        }
    }

    private function doConfigAppenders(array $config): void
    {
        if (isset($config['appenders']) && is_array($config['appenders'])) {
            foreach ($config['appenders'] as $name => $appenderConfig) {
                $this->configureAppender($name, $appenderConfig);
            }
        }
    }

    private function doConfigRootLogger(LoggerHierarchy $hierarchy, array $config): void
    {
        if (isset($config['rootLogger'])) {
            $this->configureRootLogger($hierarchy, $config['rootLogger']);
        }
    }

    private function doConfigLoggers(LoggerHierarchy $hierarchy, array $config): void
    {
        if (isset($config['loggers']) && is_array($config['loggers'])) {
            foreach ($config['loggers'] as $loggerName => $loggerConfig) {
                $this->configureOtherLogger($hierarchy, $loggerName, $loggerConfig);
            }
        }
    }

    private function doConfigRenderers(LoggerHierarchy $hierarchy, array $config): void
    {
        if (isset($config['renderers']) && is_array($config['renderers'])) {
            foreach ($config['renderers'] as $rendererConfig) {
                $this->configureRenderer($hierarchy, $rendererConfig);
            }
        }
    }

    private function doConfigDefaultRenderer(LoggerHierarchy $hierarchy, array $config): void
    {
        if (isset($config['defaultRenderer'])) {
            $this->configureDefaultRenderer($hierarchy, $config['defaultRenderer']);
        }
    }

    private function configureRenderer(LoggerHierarchy $hierarchy, array $config): void
    {
        if (empty($config['renderingClass'])) {
            $this->warn('Rendering class not specified. Skipping renderer definition.');
            return;
        }

        if (empty($config['renderedClass'])) {
            $this->warn('Rendered class not specified. Skipping renderer definition.');
            return;
        }

        $hierarchy->getRendererMap()->addRenderer($config['renderedClass'], $config['renderingClass']);
    }

    private function configureDefaultRenderer(LoggerHierarchy $hierarchy, string $class): void
    {
        if (empty($class)) {
            $this->warn('Rendering class not specified. Skipping default renderer definition.');
            return;
        }

        $hierarchy->getRendererMap()->setDefaultRenderer($class);
    }

    private function configureAppender(string $name, array $config): void
    {
        $namespace = 'Log4Php\\Appenders\\';

        $class = $config['class'];
        if (empty($class)) {
            $this->warn("No class given for appender [$name]. Skipping appender definition.");
            return;
        }

        $class = $namespace . $class;

        if (!class_exists($class)) {
            $log  = "Invalid class [$class ] given for appender [$name]. ";
            $log .= 'Class does not exist. Skipping appender definition.';
            $this->warn($log);
            return;
        }

        $appender = new $class($name);
        if (!($appender instanceof LoggerAppender)) {
            $log  = "Invalid class [$class] given for appender [$name]";
            $log .= ' Not a valid LoggerAppender class. Skipping appender definition.';
            $this->warn($log);
            return;
        }

        $this->parseAppenderThreshold($name, $config, $appender);
        $this->parseAppenderLayout($config, $appender);
        $this->parseFilters($config, $appender);
        $this->parseOptions($config, $appender);

        $appender->activateOptions();
        $this->appenders[$name] = $appender;
    }

    private function parseAppenderThreshold(string $name, array $config, LoggerAppender $appender): void
    {
        if (isset($config['threshold'])) {
            $threshold = LoggerLevel::toLevel($config['threshold']);

            if ($threshold instanceof LoggerLevel) {
                $appender->setThreshold($threshold);
                return;
            }

            $log  = 'Invalid threshold value [' . $config['threshold'] . '] specified for appender [' . $name . ']. ';
            $log .= 'Ignoring threshold definition.';
            $this->warn($log);
        }
    }

    private function parseAppenderLayout(array $config, LoggerAppender $appender): void
    {
        if ($appender->requiresLayout() && isset($config['layout'])) {
            $this->createAppenderLayout($appender, $config['layout']);
        }
    }

    private function parseFilters(array $config, LoggerAppender $appender): void
    {
        if (isset($config['filters']) && is_array($config['filters'])) {
            foreach ($config['filters'] as $filterConfig) {
                $this->createAppenderFilter($appender, $filterConfig);
            }
        }
    }

    private function parseOptions(array $config, LoggerAppender $appender): void
    {
        if (isset($config['params'])) {
            $this->setOptions($appender, $config['params']);
        }
    }

    private function createAppenderLayout(LoggerAppender $appender, array $config): void
    {
        $name  = $appender->getName();
        $class = $config['class'];

        if (empty($class)) {
            $this->warn("Layout class not specified for appender [$name]. Reverting to default layout.");
            return;
        }

        $namespace = 'Log4Php\\Layouts\\';
        $class     = $namespace . $class;

        if (!class_exists($class)) {
            $log = "Nonexistent layout class [$class] specified for appender [$name]. Reverting to default layout";
            $this->warn($log);
            return;
        }

        $layout = new $class();
        if (!($layout instanceof LoggerLayout)) {
            $log = "Invalid layout class [$class] sepcified for appender [$name]. Reverting to default layout.";
            $this->warn($log);
            return;
        }

        if (isset($config['params'])) {
            $this->setOptions($layout, $config['params']);
        }

        $layout->activateOptions();
        $appender->setLayout($layout);
    }

    private function createAppenderFilter(LoggerAppender $appender, array $config): void
    {
        $name  = $appender->getName();
        $class = $config['class'];
        if (!class_exists($class)) {
            $log = "Nonexistent filter class [$class] specified on appender [$name]. Skipping filter definition.";
            $this->warn($log);
            return;
        }

        $filter = new $class();
        if (!($filter instanceof LoggerFilter)) {
            $this->warn("Invalid filter class [$class] sepcified on appender [$name]. Skipping filter definition.");
            return;
        }

        if (isset($config['params'])) {
            $this->setOptions($filter, $config['params']);
        }

        $filter->activateOptions();
        $appender->addFilter($filter);
    }

    private function configureRootLogger(LoggerHierarchy $hierarchy, array $config): void
    {
        $logger = $hierarchy->getRootLogger();
        $this->configureLogger($logger, $config);
    }

    private function configureOtherLogger(LoggerHierarchy $hierarchy, string $name, array $config): void
    {
        $logger = $hierarchy->getLogger($name);
        $this->configureLogger($logger, $config);
    }

    private function configureLogger(Logger $logger, array $config): void
    {
        $loggerName = $logger->getName();

        $this->setLoggerLevel($logger, $config, $loggerName);
        $this->linkAppenders($logger, $config, $loggerName);
        $this->setLoggerAdditivity($logger, $config, $loggerName);
    }

    private function setLoggerLevel(Logger $logger, array $config, string $loggerName): void
    {
        if (isset($config['level'])) {
            $level = LoggerLevel::toLevel($config['level']);
            if (isset($level)) {
                $logger->setLevel($level);
                return;
            }

            $log  = 'Invalid level value [' . $config['level'] . '] specified for logger [' . $loggerName . '].';
            $log .= ' Ignoring level definition.';
            $this->warn($log);
        }
    }

    private function linkAppenders(Logger $logger, array $config, string $loggerName): void
    {
        if (isset($config['appenders'])) {
            foreach ($config['appenders'] as $appenderName) {
                if (isset($this->appenders[$appenderName])) {
                    $logger->addAppender($this->appenders[$appenderName]);
                    continue;
                }
                $this->warn("Nonexistent appender [$appenderName] linked to logger [$loggerName].");
            }
        }
    }

    private function setLoggerAdditivity(Logger $logger, array $config, string $loggerName): void
    {
        if (isset($config['additivity'])) {
            try {
                $additivity = LoggerOptionConverter::toBooleanEx($config['additivity']);
                $logger->setAdditivity($additivity);
            } catch (\Exception $ex) {
                $log  = 'Invalid additivity value [' . $config['additivity'] . '] specified for logger ';
                $log .= '[' . $loggerName . ']. Ignoring additivity setting.';
                $this->warn($log);
            }
        }
    }

    private function setOptions(mixed $object, array $options): void
    {
        foreach ($options as $name => $value) {
            $setter = 'set' . $name;
            if (method_exists($object, $setter)) {
                $object->$setter($value);
                continue;
            }
            $class = get_class($object);
            $this->warn("Nonexistent option [$name] specified on [$class]. Skipping.");
        }
    }

    private function warn(string $message): void
    {
        trigger_error("log4php: $message", E_USER_WARNING);
    }
}
