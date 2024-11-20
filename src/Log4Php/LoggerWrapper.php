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
        $this->logger->fatal($this->interpolate($message, $context));
    }

    public function alert(string|Stringable $message, array $context = []): void
    {
        $this->logger->fatal($this->interpolate($message, $context));
    }

    public function critical(string|Stringable $message, array $context = []): void
    {
        $this->logger->fatal($this->interpolate($message, $context));
    }

    public function error(string|Stringable $message, array $context = []): void
    {
        $this->logger->error($this->interpolate($message, $context));
    }

    public function warning(string|Stringable $message, array $context = []): void
    {
        $this->logger->warn($this->interpolate($message, $context));
    }

    public function notice(string|Stringable $message, array $context = []): void
    {
        $this->logger->info($this->interpolate($message, $context));
    }

    public function info(string|Stringable $message, array $context = []): void
    {
        $this->logger->info($this->interpolate($message, $context));
    }

    public function debug(string|Stringable $message, array $context = []): void
    {
        $this->logger->debug($this->interpolate($message, $context));
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

        $this->logger->log($level, $this->interpolate($message, $context));
    }

    private function interpolate($message, array $context = []): string
    {
        $replace = [];

        foreach ($context as $key => $value) {
            if (!is_array($value) && (!is_object($value) || method_exists($value, '__toString'))) {
                $replace['{' . $key . '}'] = $value;
            }
            if (is_array($value)) {
                $concatValue = ' ';
                foreach ($value as $subKey => $subValue) {
                    $concatValue .= "$subKey => $subValue ";
                }
                $replace['{' . $key . '}'] = "[$concatValue]";
            }
        }

        return strtr($message, $replace);
    }
}
