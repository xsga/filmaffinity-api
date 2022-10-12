<?php

/**
 * LoggerWrapper.
 *
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace Log4Php;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Log4Php\Logger;
use Log4Php\LoggerLevel;
use Stringable;

/**
 * LoggerWrapper class.
 */
class LoggerWrapper implements LoggerInterface
{
    /**
     * Log4Php logger.
     *
     * @var Logger
     *
     * @access private
     */
    private $logger;

    /**
     * Constructor.
     *
     * @param Logger $logger Log4Php instance.
     *
     * @access public
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * System is unusable.
     *
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function emergency(string|Stringable $message, array $context = array()): void
    {
        $this->logger->fatal($message);
    }

    /**
     * Action must be taken immediately.
     *
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function alert(string|Stringable $message, array $context = array()): void
    {
        $this->logger->fatal($message);
    }

    /**
     * Critical conditions.
     *
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function critical(string|Stringable $message, array $context = array()): void
    {
        $this->logger->fatal($message);
    }

    /**
     * Runtime errors that do not require immediate action but should
     * be logged and monitored.
     *
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function error(string|Stringable $message, array $context = array()): void
    {
        $this->logger->error($message);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function warning(string|Stringable $message, array $context = array()): void
    {
        $this->logger->warn($message);
    }
    /**
     * Normal but significant events.
     *
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function notice(string|Stringable $message, array $context = array()): void
    {
        $this->logger->info($message);
    }

    /**
     * Interesting events.
     *
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function info(string|Stringable $message, array $context = array()): void
    {
        $this->logger->info($message);
    }

    /**
     * Detailed debug information.
     *
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function debug(string|Stringable $message, array $context = array()): void
    {
        $this->logger->debug($message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed             $level   Log level.
     * @param string|Stringable $message Message.
     * @param array             $context Context.
     *
     * @return void
     *
     * @access public
     */
    public function log(mixed $level, string|Stringable $message, array $context = array()): void
    {
        $levels = array(
            LogLevel::EMERGENCY => LoggerLevel::FATAL,
            LogLevel::ALERT     => LoggerLevel::FATAL,
            LogLevel::CRITICAL  => LoggerLevel::FATAL,
            LogLevel::ERROR     => LoggerLevel::ERROR,
            LogLevel::WARNING   => LoggerLevel::WARN,
            LogLevel::NOTICE    => LoggerLevel::WARN,
            LogLevel::INFO      => LoggerLevel::INFO,
            LogLevel::DEBUG     => LoggerLevel::DEBUG
        );

        if (!array_key_exists($level, $levels)) {
            $level = LoggerLevel::INFO;
        }//end if

        $level = LoggerLevel::toLevel($levels[$level]);

        $this->logger->log($level, $message);
    }
}
