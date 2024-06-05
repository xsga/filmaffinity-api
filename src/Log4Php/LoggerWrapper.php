<?php

namespace Log4Php;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Log4Php\Logger;
use Log4Php\LoggerLevel;
use Stringable;

class LoggerWrapper implements LoggerInterface
{
    public function __construct(private Logger $logger)
    {
    }

    public function emergency(string|Stringable $message, array $context = []): void
    {
        $this->logger->fatal($message);
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->logger->fatal($message);
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->logger->fatal($message);
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        $this->logger->error($message);
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->logger->warn($message);
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->logger->info($message);
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        $this->logger->info($message);
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->logger->debug($message);
    }

    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        $levels = [
            LogLevel::EMERGENCY => LoggerLevel::FATAL,
            LogLevel::ALERT     => LoggerLevel::FATAL,
            LogLevel::CRITICAL  => LoggerLevel::FATAL,
            LogLevel::ERROR     => LoggerLevel::ERROR,
            LogLevel::WARNING   => LoggerLevel::WARN,
            LogLevel::NOTICE    => LoggerLevel::WARN,
            LogLevel::INFO      => LoggerLevel::INFO,
            LogLevel::DEBUG     => LoggerLevel::DEBUG
        ];

        if (!array_key_exists($level, $levels)) {
            $level = LoggerLevel::INFO;
        }

        $level = LoggerLevel::toLevel($levels[$level]);

        $this->logger->log($level, $message);
    }
}
