<?php

/**
 * LoggerConfiguratorDefault.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP Version 8
 *
 * @package    Log4Php
 * @subpackage Configurators
 */

/**
 * Namespace.
 */
namespace Log4Php\Configurators;

/**
 * Import dependencies.
 */
use Log4Php\LoggerConfigurator;
use Log4Php\LoggerHierarchy;
use Log4Php\LoggerException;
use Log4Php\LoggerLevel;
use Log4Php\LoggerAppender;
use Log4Php\LoggerFilter;
use Log4Php\Logger;
use Log4Php\LoggerLayout;
use Log4Php\Helpers\LoggerOptionConverter;

/**
 * Default implementation of the logger configurator.
 *
 * Configures log4php based on a provided configuration file or array.
 */
class LoggerConfiguratorDefault implements LoggerConfigurator
{
    /**
     * XML configuration file format.
     *
     * @var string
     *
     * @access public
     */
    public const FORMAT_XML = 'xml';

    /**
     * PHP configuration file format.
     *
     * @var string
     *
     * @access public
     */
    public const FORMAT_PHP = 'php';

    /**
     * INI (properties) configuration file format.
     *
     * @var string
     *
     * @access public
     */
    public const FORMAT_INI = 'ini';

    /**
     * Defines which adapter should be used for parsing which format.
     *
     * @var array
     *
     * @access private
     */
    private $adapters = array(
                         self::FORMAT_XML => 'LoggerConfigurationAdapterXML',
                         self::FORMAT_INI => 'LoggerConfigurationAdapterINI',
                         self::FORMAT_PHP => 'LoggerConfigurationAdapterPHP',
                        );

    /**
     * Default configuration; used if no configuration file is provided.
     *
     * @var array
     *
     * @access private
     */
    private static $defaultConfiguration = array(
        'threshold'  => 'ALL',
        'rootLogger' => array(
            'level'     => 'DEBUG',
            'appenders' => array('default'),
        ),
        'appenders'  => array(
            'default' => array(
                'class' => 'LoggerAppenderEcho'
            ),
        )
    );

    /**
     * Holds the appenders before they are linked to loggers.
     *
     * @var array
     *
     * @access private
     */
    private $appenders = array();

    /**
     * Configure.
     *
     * Configures log4php based on the given configuration. The input can either be a path to the config file, or a PHP
     * array holding the configuration.
     *
     * If no configuration is given, or if the given configuration cannot be parsed for whatever reason, a warning will
     * be issued, and log4php will use the default configuration contained in $defaultConfiguration.
     *
     * @param LoggerHierarchy   $hierarchy The hierarchy on which to perform the configuration.
     * @param string|array|null $input     Either path to the config file or the configuration as an array.
     *
     * @return void
     *
     * @access public
     */
    public function configure(LoggerHierarchy $hierarchy, string|array|null $input = null): void
    {
        $config = $this->parse($input);
        $this->doConfigure($hierarchy, $config);
    }

    /**
     * Parses the given configuration and returns the parsed configuration as a PHP array.
     *
     * Does not perform any configuration.
     *
     * If no configuration is given, or if the given configuration cannot be parsed for whatever reason, a warning will
     * be issued, and the default configuration will be returned $defaultConfiguration.
     *
     * @param string|array $input Either path to the config file or the configuration as an array.
     *
     * @return array
     *
     * @access public
     */
    public function parse(string|array $input): array
    {
        // No input - use default configuration.
        if (empty($input)) {
            return static::$defaultConfiguration;
        }//end if

        // Array input - contains configuration within the array.
        if (is_array($input)) {
            return $input;
        }//end if

        // String input - contains path to configuration file.
        try {
            $config = $this->parseFile($input);
        } catch (LoggerException $e) {
            $this->warn('Configuration failed.' . $e->getMessage() . 'Using default configuration.');
            $config = static::$defaultConfiguration;
        }//end try

        return $config;
    }

    /**
     * Returns the default log4php configuration.
     *
     * @return array
     *
     * @access public
     */
    public static function getDefaultConfiguration(): array
    {
        return static::$defaultConfiguration;
    }

    /**
     * Parse file.
     *
     * Loads the configuration file from the given URL, determines which adapter to use, converts the configuration
     * to a PHP array and returns it.
     *
     * @param string $url Path to the config file.
     *
     * @return array
     *
     * @throws LoggerException If the configuration file cannot be loaded, or if the parsing fails.
     *
     * @access private
     */
    private function parseFile(string $url): array
    {
        if (!file_exists($url)) {
            throw new LoggerException('File not found at [' . $url . '].');
        }//end if

        $namespace = 'Log4Php\\Configurators\\';

        $type         = $this->getConfigType($url);
        $adapterClass = $namespace . $this->adapters[$type];
        $adapter      = new $adapterClass();

        return $adapter->convert($url);
    }

    /**
     * Determines configuration file type based on the file extension.
     *
     * @param string $url Url.
     *
     * @return string
     *
     * @throws LoggerException
     *
     * @access private
     */
    private function getConfigType(string $url): string
    {
        $info = pathinfo($url);
        $ext  = strtolower($info['extension']);

        switch ($ext) {
            case 'xml':
                return static::FORMAT_XML;
            case 'ini':
                return static::FORMAT_INI;
            case 'properties':
                return static::FORMAT_INI;
            case 'php':
                return static::FORMAT_PHP;
            default:
                throw new LoggerException("Unsupported configuration file extension: $ext");
        }//end switch
    }

    /**
     * Constructs the logger hierarchy based on configuration.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function doConfigure(LoggerHierarchy $hierarchy, array $config): void
    {
        $this->doConfigThreshold($hierarchy, $config);
        $this->doConfigAppenders($config);
        $this->doConfigRootLogger($hierarchy, $config);
        $this->doConfigLoggers($hierarchy, $config);
        $this->doConfigRenderers($hierarchy, $config);
        $this->doConfigDefaultRenderer($hierarchy, $config);
    }

    /**
     * Config threshold.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function doConfigThreshold(LoggerHierarchy $hierarchy, array $config): void
    {
        if (isset($config['threshold'])) {
            $threshold = LoggerLevel::toLevel($config['threshold']);

            if (isset($threshold)) {
                $hierarchy->setThreshold($threshold);
            } else {
                $log  = 'Invalid threshold value [' . $config['threshold'] . ']';
                $log .= ' specified. Ignoring threshold definition.';
                $this->warn($log);
            }//end if
        }//end if
    }

    /**
     * Config appenders.
     *
     * @param array $config Config.
     *
     * @return void
     *
     * @access private
     */
    private function doConfigAppenders(array $config): void
    {
        // Configure appenders and add them to the appender pool.
        if (isset($config['appenders']) && is_array($config['appenders'])) {
            foreach ($config['appenders'] as $name => $appenderConfig) {
                $this->configureAppender($name, $appenderConfig);
            }//end foreach
        }//end if
    }

    /**
     * Config root logger.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function doConfigRootLogger(LoggerHierarchy $hierarchy, array $config): void
    {
        // Configure root logger.
        if (isset($config['rootLogger'])) {
            $this->configureRootLogger($hierarchy, $config['rootLogger']);
        }//end if
    }

    /**
     * Config loggers.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function doConfigLoggers(LoggerHierarchy $hierarchy, array $config): void
    {
        // Configure loggers.
        if (isset($config['loggers']) && is_array($config['loggers'])) {
            foreach ($config['loggers'] as $loggerName => $loggerConfig) {
                $this->configureOtherLogger($hierarchy, $loggerName, $loggerConfig);
            }//end foreach
        }//end if
    }

    /**
     * Config renderers.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function doConfigRenderers(LoggerHierarchy $hierarchy, array $config): void
    {
        // Configure renderers.
        if (isset($config['renderers']) && is_array($config['renderers'])) {
            foreach ($config['renderers'] as $rendererConfig) {
                $this->configureRenderer($hierarchy, $rendererConfig);
            }//end foreach
        }//end if
    }

    /**
     * Config default renderer.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function doConfigDefaultRenderer(LoggerHierarchy $hierarchy, array $config): void
    {
        if (isset($config['defaultRenderer'])) {
            $this->configureDefaultRenderer($hierarchy, $config['defaultRenderer']);
        }//end if
    }

    /**
     * Configure render.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function configureRenderer(LoggerHierarchy $hierarchy, array $config): void
    {
        if (empty($config['renderingClass'])) {
            $this->warn('Rendering class not specified. Skipping renderer definition.');
            return;
        }//end if

        if (empty($config['renderedClass'])) {
            $this->warn('Rendered class not specified. Skipping renderer definition.');
            return;
        }//end if

        // Error handling performed by RendererMap.
        $hierarchy->getRendererMap()->addRenderer($config['renderedClass'], $config['renderingClass']);
    }

    /**
     * Configure default renderer.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param string          $class     Class.
     *
     * @return void
     *
     * @access private
     */
    private function configureDefaultRenderer(LoggerHierarchy $hierarchy, string $class): void
    {
        if (empty($class)) {
            $this->warn('Rendering class not specified. Skipping default renderer definition.');
            return;
        }//end if

        // Error handling performed by RendererMap.
        $hierarchy->getRendererMap()->setDefaultRenderer($class);
    }

    /**
     * Configures an appender based on given config and saves it to $appenders array.
     *
     * @param string $name   Appender name.
     * @param array  $config Appender configuration options.
     *
     * @return void
     *
     * @access private
     */
    private function configureAppender(string $name, array $config): void
    {
        $namespace = 'Log4Php\\Appenders\\';

        // Parse appender class.
        $class = $config['class'];
        if (empty($class)) {
            $this->warn("No class given for appender [$name]. Skipping appender definition.");
            return;
        }//end if

        $class = $namespace . $class;

        if (!class_exists($class)) {
            $log  = "Invalid class [$class ] given for appender [$name]. ";
            $log .= 'Class does not exist. Skipping appender definition.';
            $this->warn($log);
            return;
        }//end if

        // Instantiate the appender.
        $appender = new $class($name);
        if (!($appender instanceof LoggerAppender)) {
            $log  = "Invalid class [$class] given for appender [$name]";
            $log .= ' Not a valid LoggerAppender class. Skipping appender definition.';
            $this->warn($log);
            return;
        }//end if

        $this->parseAppenderThreshold($name, $config, $appender);

        $this->parseAppenderLayout($config, $appender);

        $this->parseFilters($config, $appender);

        $this->parseOptions($config, $appender);

        // Activate and save for later linking to loggers.
        $appender->activateOptions();
        $this->appenders[$name] = $appender;
    }

    /**
     * Parse the appender threshold.
     *
     * @param string         $name     Appender name.
     * @param array          $config   Appender configuration options.
     * @param LoggerAppender $appender Appender.
     *
     * @return void
     *
     * @access private
     */
    private function parseAppenderThreshold(string $name, array $config, LoggerAppender $appender): void
    {
        // Parse the appender threshold.
        if (isset($config['threshold'])) {
            $threshold = LoggerLevel::toLevel($config['threshold']);

            if ($threshold instanceof LoggerLevel) {
                $appender->setThreshold($threshold);
                return;
            }//end if

            $log  = 'Invalid threshold value [' . $config['threshold'] . '] specified for appender [' . $name . ']. ';
            $log .= 'Ignoring threshold definition.';
            $this->warn($log);
        }//end if
    }

    /**
     * Parse the appender layout.
     *
     * @param array          $config   Appender configuration options.
     * @param LoggerAppender $appender Appender.
     *
     * @return void
     *
     * @access private
     */
    private function parseAppenderLayout(array $config, LoggerAppender $appender): void
    {
        // Parse the appender layout.
        if ($appender->requiresLayout() && isset($config['layout'])) {
            $this->createAppenderLayout($appender, $config['layout']);
        }//end if
    }

    /**
     * Parse filters.
     *
     * @param array          $config   Appender configuration options.
     * @param LoggerAppender $appender Appender.
     *
     * @return void
     *
     * @access private
     */
    private function parseFilters(array $config, LoggerAppender $appender): void
    {
        // Parse filters.
        if (isset($config['filters']) && is_array($config['filters'])) {
            foreach ($config['filters'] as $filterConfig) {
                $this->createAppenderFilter($appender, $filterConfig);
            }//end foreach
        }//end if
    }

    /**
     * Parse options.
     *
     * @param array          $config   Appender configuration options.
     * @param LoggerAppender $appender Appender.
     *
     * @return void
     *
     * @access private
     */
    private function parseOptions(array $config, LoggerAppender $appender): void
    {
        // Set options if any.
        if (isset($config['params'])) {
            $this->setOptions($appender, $config['params']);
        }//end if
    }

    /**
     * Parses layout config, creates the layout and links it to the appender.
     *
     * @param LoggerAppender $appender Appender.
     * @param array          $config   Layout configuration.
     *
     * @return void
     *
     * @access private
     */
    private function createAppenderLayout(LoggerAppender $appender, array $config): void
    {
        $name  = $appender->getName();
        $class = $config['class'];

        if (empty($class)) {
            $this->warn("Layout class not specified for appender [$name]. Reverting to default layout.");
            return;
        }//end if

        $namespace = 'Log4Php\\Layouts\\';
        $class     = $namespace . $class;

        if (!class_exists($class)) {
            $log = "Nonexistent layout class [$class] specified for appender [$name]. Reverting to default layout";
            $this->warn($log);
            return;
        }//end if

        $layout = new $class();
        if (!($layout instanceof LoggerLayout)) {
            $log = "Invalid layout class [$class] sepcified for appender [$name]. Reverting to default layout.";
            $this->warn($log);
            return;
        }//end if

        if (isset($config['params'])) {
            $this->setOptions($layout, $config['params']);
        }//end if

        $layout->activateOptions();
        $appender->setLayout($layout);
    }

    /**
     * Parses filter config, creates the filter and adds it to the appender's filter chain.
     *
     * @param LoggerAppender $appender Appender.
     * @param array          $config   Filter configuration.
     *
     * @return void
     *
     * @access private
     */
    private function createAppenderFilter(LoggerAppender $appender, array $config): void
    {
        $name  = $appender->getName();
        $class = $config['class'];
        if (!class_exists($class)) {
            $log = "Nonexistent filter class [$class] specified on appender [$name]. Skipping filter definition.";
            $this->warn($log);
            return;
        }//end if

        $filter = new $class();
        if (!($filter instanceof LoggerFilter)) {
            $this->warn("Invalid filter class [$class] sepcified on appender [$name]. Skipping filter definition.");
            return;
        }//end if

        if (isset($config['params'])) {
            $this->setOptions($filter, $config['params']);
        }//end if

        $filter->activateOptions();
        $appender->addFilter($filter);
    }

    /**
     * Configures the root logger.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function configureRootLogger(LoggerHierarchy $hierarchy, array $config): void
    {
        $logger = $hierarchy->getRootLogger();
        $this->configureLogger($logger, $config);
    }

    /**
     * Configures a logger which is not root.
     *
     * @param LoggerHierarchy $hierarchy Hierarchy.
     * @param string          $name      Name.
     * @param array           $config    Config.
     *
     * @return void
     *
     * @access private
     */
    private function configureOtherLogger(LoggerHierarchy $hierarchy, $name, array $config): void
    {
        // Get logger from hierarchy (this creates it if it doesn't already exist).
        $logger = $hierarchy->getLogger($name);
        $this->configureLogger($logger, $config);
    }

    /**
     * Configures a logger.
     *
     * @param Logger $logger The logger to configure.
     * @param array  $config Logger configuration options.
     *
     * @return void
     *
     * @access private
     */
    private function configureLogger(Logger $logger, array $config): void
    {
        $loggerName = $logger->getName();

        $this->setLoggerLevel($logger, $config, $loggerName);
        $this->linkAppenders($logger, $config, $loggerName);
        $this->setLoggerAdditivity($logger, $config, $loggerName);
    }

    /**
     * Set logger level.
     *
     * @param Logger $logger     The logger to configure.
     * @param array  $config     Logger configuration options.
     * @param string $loggerName Logger name.
     *
     * @return void
     *
     * @access private
     */
    private function setLoggerLevel(Logger $logger, array $config, string $loggerName): void
    {
        // Set logger level.
        if (isset($config['level'])) {
            $level = LoggerLevel::toLevel($config['level']);
            if (isset($level)) {
                $logger->setLevel($level);
                return;
            }//end if

            $log  = 'Invalid level value [' . $config['level'] . '] specified for logger [' . $loggerName . '].';
            $log .= ' Ignoring level definition.';
            $this->warn($log);
        }//end if
    }

    /**
     * Link appenders to logger.
     *
     * @param Logger $logger     The logger to configure.
     * @param array  $config     Logger configuration options.
     * @param string $loggerName Logger name.
     *
     * @return void
     *
     * @access private
     */
    private function linkAppenders(Logger $logger, array $config, string $loggerName): void
    {
        // Link appenders to logger.
        if (isset($config['appenders'])) {
            foreach ($config['appenders'] as $appenderName) {
                if (isset($this->appenders[$appenderName])) {
                    $logger->addAppender($this->appenders[$appenderName]);
                    continue;
                }//end if
                $this->warn("Nonexistent appender [$appenderName] linked to logger [$loggerName].");
            }//end foreach
        }//end if
    }

    /**
     * Set logger additivity.
     *
     * @param Logger $logger     The logger to configure.
     * @param array  $config     Logger configuration options.
     * @param string $loggerName Logger name.
     *
     * @return void
     *
     * @access private
     */
    private function setLoggerAdditivity(Logger $logger, array $config, string $loggerName): void
    {
        // Set logger additivity.
        if (isset($config['additivity'])) {
            try {
                $additivity = LoggerOptionConverter::toBooleanEx($config['additivity']);
                $logger->setAdditivity($additivity);
            } catch (\Exception $ex) {
                $log  = 'Invalid additivity value [' . $config['additivity'] . '] specified for logger ';
                $log .= '[' . $loggerName . ']. Ignoring additivity setting.';
                $this->warn($log);
            }//end try
        }//end if
    }

    /**
     * Helper method which applies given options to an object which has setters for these options.
     *
     * For example, if options are:
     * <code>
     * array(
     *     'file' => '/tmp/myfile.log',
     *     'append' => true
     * )
     * </code>
     *
     * This method will call:
     * <code>
     * $object->setFile('/tmp/myfile.log')
     * $object->setAppend(true)
     * </code>
     *
     * If required setters do not exist, it will produce a warning.
     *
     * @param mixed $object  The object to configure.
     * @param array $options Options.
     *
     * @return void
     *
     * @access private
     */
    private function setOptions($object, array $options): void
    {
        foreach ($options as $name => $value) {
            $setter = 'set' . $name;
            if (method_exists($object, $setter)) {
                $object->$setter($value);
                continue;
            }//end if
            $class = get_class($object);
            $this->warn("Nonexistent option [$name] specified on [$class]. Skipping.");
        }//end foreach
    }

    /**
     * Helper method to simplify error reporting.
     *
     * @param string $message Message.
     *
     * @return void
     *
     * @access private
     */
    private function warn($message): void
    {
        trigger_error("log4php: $message", E_USER_WARNING);
    }
}
