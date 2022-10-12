<?php

/**
 * Logger.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP Version 8
 *
 * @package Log4Php
 * @version SVN: $Id: Logger.php 1395241 2012-10-07 08:28:53Z ihabunek $
 * @link    http://logging.apache.org/log4php
 */

/**
 * Namespace.
 */
namespace Log4Php;

/**
 * Import dependencies.
 */
use Log4Php\Configurators\LoggerConfiguratorDefault;

/**
 * This is the central class in the log4php package.
 * All logging operations are done through this class.
 */
class Logger
{
    /**
     * Logger additivity.
     * If set to true then child loggers will inherit the appenders of their ancestors by default.
     *
     * @var boolean
     *
     * @access private
     */
    private $additive = true;

    /**
     * The Logger's fully qualified class name.
     *
     * @var string
     *
     * @access private
     */
    private $fqcn = 'Logger';

    /**
     * The assigned Logger level.
     *
     * @var LoggerLevel|null
     *
     * @access private
     */
    private $level = null;

    /**
     * The name of this Logger instance.
     *
     * @var string
     *
     * @access private
     */
    private $name;

    /**
     * The parent logger. Set to null if this is the root logger.
     *
     * @var Logger|null
     *
     * @access private
     */
    private $parent = null;

    /**
     * A collection of appenders linked to this logger.
     *
     * @var array
     *
     * @access private
     */
    private $appenders = array();

    /**
     * The logger hierarchy used by log4php.
     *
     * @var LoggerHierarchy|null
     *
     * @access private
     */
    private static $hierarchy = null;

    /**
     * Inidicates if log4php has been initialized.
     *
     * @var boolean
     *
     * @access private
     */
    private static $initialized = false;

    /**
     * Constructor.
     *
     * @param string $name Name of the logger.
     *
     * @access public
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the logger name.
     *
     * @return string
     *
     * @access public
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the parent Logger. Can be null if this is the root logger.
     *
     * @return Logger|null
     *
     * @access public
     */
    public function getParent(): Logger|null
    {
        return $this->parent;
    }

    /**
     * Log a message object with the TRACE level.
     *
     * @param mixed      $message   Message.
     * @param \Exception $throwable Optional throwable information to include in the logging event.
     *
     * @return void
     *
     * @access public
     */
    public function trace(mixed $message, \Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelTrace(), $message, $throwable);
    }

    /**
     * Log a message object with the DEBUG level.
     *
     * @param mixed      $message   Message.
     * @param \Exception $throwable Optional throwable information to include in the logging event.
     *
     * @return void
     *
     * @access public
     */
    public function debug(mixed $message, \Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelDebug(), $message, $throwable);
    }

    /**
     * Log a message object with the INFO Level.
     *
     * @param mixed      $message   Message.
     * @param \Exception $throwable Optional throwable information to include in the logging event.
     *
     * @return void
     *
     * @access public
     */
    public function info(mixed $message, \Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelInfo(), $message, $throwable);
    }

    /**
     * Log a message with the WARN level.
     *
     * @param mixed      $message   Message.
     * @param \Exception $throwable Optional throwable information to include in the logging event.
     *
     * @return void
     *
     * @access public
     */
    public function warn(mixed $message, \Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelWarn(), $message, $throwable);
    }

    /**
     * Log a message object with the ERROR level.
     *
     * @param mixed      $message   Message.
     * @param \Exception $throwable Optional throwable information to include in the logging event.
     *
     * @return void
     *
     * @access public
     */
    public function error(mixed $message, \Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelError(), $message, $throwable);
    }

    /**
     * Log a message object with the FATAL level.
     *
     * @param mixed      $message   Message.
     * @param \Exception $throwable Optional throwable information to include in the logging event.
     *
     * @return void
     *
     * @access public
     */
    public function fatal(mixed $message, \Exception $throwable = null): void
    {
        $this->log(LoggerLevel::getLevelFatal(), $message, $throwable);
    }

    /**
     * Log a message using the provided logging level.
     *
     * @param LoggerLevel  $level     The logging level.
     * @param mixed        $message   Message to log.
     * @param \Exception   $throwable Optional throwable information to include in the logging event.
     *
     * @return void
     *
     * @access public
     */
    public function log(LoggerLevel $level, mixed $message, \Exception $throwable = null): void
    {
        if ($this->isEnabledFor($level)) {
            $event = new LoggerLoggingEvent($this->fqcn, $this, $level, $message, null, $throwable);
            $this->callAppenders($event);
        }//end if

        // Forward the event upstream if additivity is turned on.
        if (!is_null($this->parent) && $this->getAdditivity()) {
            // Use the event if already created.
            if (isset($event)) {
                $this->parent->logEvent($event);
            } else {
                $this->parent->log($level, $message, $throwable);
            }//end if
        }//end if
    }

    /**
     * Logs an already prepared logging event object.
     *
     * @param LoggerLoggingEvent $event Event.
     *
     * @return void
     *
     * @access public
     */
    public function logEvent(LoggerLoggingEvent $event): void
    {
        if ($this->isEnabledFor($event->getLevel())) {
            $this->callAppenders($event);
        }//end if

        // Forward the event upstream if additivity is turned on.
        if (!is_null($this->parent) && $this->getAdditivity()) {
            $this->parent->logEvent($event);
        }//end if
    }

    /**
     * If assertion parameter evaluates as false, then logs the message using the ERROR level.
     *
     * @param boolean $assertion Assertion.
     * @param string  $msg       Message to log.
     *
     * @return void
     *
     * @access public
     */
    public function assertLog(bool $assertion = true, string $msg = ''): void
    {
        if (!$assertion) {
            $this->error($msg);
        }//end if
    }

    /**
     * This method creates a new logging event and logs the event without further checks.
     *
     * It should not be called directly. Use {@link trace()}, {@link debug()},
     * {@link info()}, {@link warn()}, {@link error()} and {@link fatal()}
     * wrappers.
     *
     * @param string       $fqcn      Fully qualified class name of the Logger.
     * @param \Exception   $throwable Optional throwable information to include in the logging event.
     * @param LoggerLevel  $level     Log level.
     * @param mixed        $message   Message to log.
     *
     * @return void
     *
     * @access public
     */
    public function forcedLog(string $fqcn, \Exception $throwable, LoggerLevel $level, mixed $message): void
    {
        $event = new LoggerLoggingEvent($fqcn, $this, $level, $message, null, $throwable);
        $this->callAppenders($event);

        // Forward the event upstream if additivity is turned on.
        if (!is_null($this->parent) && $this->getAdditivity()) {
            $this->parent->logEvent($event);
        }//end if
    }

    /**
     * Forwards the given logging event to all linked appenders.
     *
     * @param LoggerLoggingEvent $event Event.
     *
     * @return void
     *
     * @access public
     */
    public function callAppenders(LoggerLoggingEvent $event): void
    {
        foreach ($this->appenders as $appender) {
            $appender->doAppend($event);
        }//end foreach
    }

    /**
     * Check whether this Logger is enabled for a given Level passed as parameter.
     *
     * @param LoggerLevel $level Level.
     *
     * @return boolean
     *
     * @access public
     */
    public function isEnabledFor(LoggerLevel $level): bool
    {
        return $level->isGreaterOrEqual($this->getEffectiveLevel());
    }

    /**
     * Check whether this Logger is enabled for the TRACE Level.
     *
     * @return boolean
     *
     * @access public
     */
    public function isTraceEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelTrace());
    }

    /**
     * Check whether this Logger is enabled for the DEBUG Level.
     *
     * @return boolean
     *
     * @access public
     */
    public function isDebugEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelDebug());
    }

    /**
     * Check whether this Logger is enabled for the INFO Level.
     *
     * @return boolean
     *
     * @access public
     */
    public function isInfoEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelInfo());
    }

    /**
     * Check whether this Logger is enabled for the WARN Level.
     *
     * @return boolean
     *
     * @access public
     */
    public function isWarnEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelWarn());
    }

    /**
     * Check whether this Logger is enabled for the ERROR Level.
     *
     * @return boolean
     *
     * @access public
     */
    public function isErrorEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelError());
    }

    /**
     * Check whether this Logger is enabled for the FATAL Level.
     *
     * @return boolean
     *
     * @access public
     */
    public function isFatalEnabled(): bool
    {
        return $this->isEnabledFor(LoggerLevel::getLevelFatal());
    }

    /**
     * Adds a new appender to the Logger.
     *
     * @param LoggerAppender $appender The appender to add.
     *
     * @return void
     *
     * @access public
     */
    public function addAppender(LoggerAppender $appender): void
    {
        $appenderName = $appender->getName();
        $this->appenders[$appenderName] = $appender;
    }

    /**
     * Removes all appenders from the Logger.
     *
     * @return void
     *
     * @access public
     */
    public function removeAllAppenders(): void
    {
        foreach (array_keys($this->appenders) as $name) {
            $this->removeAppender($name);
        }//end foreach
    }

    /**
     * Remove the appender passed as parameter form the Logger.
     *
     * @param string|LoggerAppender $appender An appender name or a {@link LoggerAppender} instance.
     *
     * @return void
     *
     * @access public
     */
    public function removeAppender(string|LoggerAppender $appender): void
    {
        if ($appender instanceof LoggerAppender) {
            $appender->close();
            unset($this->appenders[$appender->getName()]);
        } elseif (isset($this->appenders[$appender])) {
            $this->appenders[$appender]->close();
            unset($this->appenders[$appender]);
        }//end if
    }

    /**
     * Returns the appenders linked to this logger as an array.
     *
     * @return array Collection of appender names
     *
     * @access public
     */
    public function getAllAppenders(): array
    {
        return $this->appenders;
    }

    /**
     * Returns a linked appender by name.
     *
     * @param string $name Appender name.
     *
     * @return LoggerAppender
     *
     * @access public
     */
    public function getAppender(string $name): LoggerAppender
    {
        return $this->appenders[$name];
    }

    /**
     * Sets the additivity flag.
     *
     * @param boolean $additive Boolean.
     *
     * @return void
     *
     * @access public
     */
    public function setAdditivity(bool $additive): void
    {
        $this->additive = $additive;
    }

    /**
     * Returns the additivity flag.
     *
     * @return boolean
     *
     * @access public
     */
    public function getAdditivity(): bool
    {
        return $this->additive;
    }

    /**
     * Starting from this Logger, search the Logger hierarchy for a non-null level and return it.
     *
     * @see    LoggerLevel
     *
     * @return LoggerLevel|null
     *
     * @access public
     */
    public function getEffectiveLevel(): LoggerLevel|null
    {
        for ($logger = $this; $logger !== null; $logger = $logger->getParent()) {
            if ($logger->getLevel() !== null) {
                return $logger->getLevel();
            }//end if
        }//end for

        return null;
    }

    /**
     * Get the assigned Logger level.
     *
     * @return LoggerLevel|null The assigned level or null if none is assigned.
     *
     * @access public
     */
    public function getLevel(): LoggerLevel|null
    {
        return $this->level;
    }

    /**
     * Set the Logger level.
     *
     * Use LoggerLevel::getLevel() methods to get a LoggerLevel object, e.g.
     * <code>$logger->setLevel(LoggerLevel::getLevelInfo());</code>
     *
     * @param LoggerLevel $level The level to set, or null to clear the logger level.
     *
     * @return void
     *
     * @access public
     */
    public function setLevel(LoggerLevel $level = null): void
    {
        $this->level = $level;
    }

    /**
     * Checks whether an appender is attached to this logger instance.
     *
     * @param LoggerAppender $appender Appender.
     *
     * @return boolean
     *
     * @access public
     */
    public function isAttached(LoggerAppender $appender): bool
    {
        return isset($this->appenders[$appender->getName()]);
    }

    /**
     * Sets the parent logger.
     *
     * @param Logger $logger Logger.
     *
     * @return void
     *
     * @access public
     */
    public function setParent(Logger $logger): void
    {
        $this->parent = $logger;
    }

    /**
     * Returns the hierarchy used by this Logger.
     *
     * Caution: do not use this hierarchy unless you have called initialize().
     * To get Loggers, use the Logger::getLogger and Logger::getRootLogger
     * methods instead of operating on on the hierarchy directly.
     *
     * @return LoggerHierarchy
     *
     * @access public
     */
    public static function getHierarchy(): LoggerHierarchy
    {
        if (is_null(static::$hierarchy)) {
            $loggerRoot = new LoggerRoot();
            static::$hierarchy = new LoggerHierarchy($loggerRoot);
        }//end if

        return static::$hierarchy;
    }

    /**
     * Returns a Logger by name. If it does not exist, it will be created.
     *
     * @param string $name The logger name.
     *
     * @return Logger
     *
     * @access public
     */
    public static function getLogger(string $name): Logger
    {
        if (!static::isInitialized()) {
            static::configure();
        }//end if

        return static::getHierarchy()->getLogger($name);
    }

    /**
     * Returns the Root Logger.
     *
     * @return LoggerRoot
     *
     * @access public
     */
    public static function getRootLogger(): LoggerRoot
    {
        if (!static::isInitialized()) {
            static::configure();
        }//end if

        return static::getHierarchy()->getRootLogger();
    }

    /**
     * Clears all Logger definitions from the logger hierarchy.
     *
     * @return void
     *
     * @access public
     */
    public static function clear(): void
    {
        static::getHierarchy()->clear();
    }

    /**
     * Destroy configurations for logger definitions.
     *
     * @return void
     *
     * @access public
     */
    public static function resetConfiguration(): void
    {
        static::getHierarchy()->resetConfiguration();
        static::getHierarchy()->clear();
        static::$initialized = false;
    }

    /**
     * Check if a given logger exists.
     *
     * @param string $name Logger name.
     *
     * @return boolean
     *
     * @access public
     */
    public static function exists(string $name): bool
    {
        return static::getHierarchy()->exists($name);
    }

    /**
     * Returns an array this whole Logger instances.
     *
     * @see    Logger
     *
     * @return array
     *
     * @access public
     */
    public static function getCurrentLoggers(): array
    {
        return static::getHierarchy()->getCurrentLoggers();
    }

    /**
     * Configures log4php.
     *
     * This method needs to be called before the first logging event has
     * occured. If this method is not called before then the default
     * configuration will be used.
     *
     * @param string|array              $configuration Either a path to the configuration file or a configuration array.
     * @param string|LoggerConfigurator $configurator  A custom configurator class.
     *
     * @return void
     *
     * @access public
     */
    public static function configure(
        string|array $configuration = null,
        string|LoggerConfigurator $configurator = null
    ): void {
        static::resetConfiguration();
        $configurator = static::getConfigurator($configurator);
        $configurator->configure(static::getHierarchy(), $configuration);
        static::$initialized = true;
    }

    /**
     * Creates a logger configurator instance based on the provided configurator class.
     *
     * If no class is given, returns an instance of the default configurator.
     *
     * @param string|LoggerConfigurator $configurator The configurator class or LoggerConfigurator instance.
     *
     * @return mixed
     *
     * @access public
     */
    private static function getConfigurator(string|LoggerConfigurator $configurator = null): mixed
    {
        if ($configurator === null) {
            return new LoggerConfiguratorDefault();
        }//end if

        if (is_object($configurator)) {
            return $configurator;
        } else {
            if (!class_exists($configurator)) {
                $log  = 'log4php: Specified configurator class ' . $configurator . ' does not exist. ';
                $log .= 'Reverting to default configurator.';

                trigger_error($log, E_USER_WARNING);

                return new LoggerConfiguratorDefault();
            }//end if

            $instance = new $configurator();

            if (!($instance instanceof LoggerConfigurator)) {
                $log  = 'log4php: Specified configurator class ' . $configurator . ' does not implement the ';
                $log .= 'LoggerConfigurator interface. Reverting to default configurator.';

                trigger_error($log, E_USER_WARNING);

                return new LoggerConfiguratorDefault();
            }//end if

            return $instance;
        }//end if
    }

    /**
     * Returns true if the log4php framework has been initialized.
     *
     * @return boolean
     *
     * @access public
     */
    public static function isInitialized(): bool
    {
        return static::$initialized;
    }
}
