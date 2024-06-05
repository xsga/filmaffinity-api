<?php

namespace Log4Php;

class LoggerAppenderPool
{
    public static array $appenders = [];

    public static function add(LoggerAppender $appender): void
    {
        $name = $appender->getName();

        if (empty($name)) {
            trigger_error('log4php: Cannot add unnamed appender to pool.', E_USER_WARNING);
            return;
        }

        if (isset(static::$appenders[$name])) {
            $log = "log4php: Appender [$name] already exists in pool. Overwriting existing appender.";
            trigger_error($log, E_USER_WARNING);
        }

        static::$appenders[$name] = $appender;
    }

    public static function get(string $name): ?LoggerAppender
    {
        return match (isset(static::$appenders[$name])) {
            true => static::$appenders[$name],
            false => null
        };
    }

    public static function delete(string $name): void
    {
        unset(static::$appenders[$name]);
    }

    public static function getAppenders(): array
    {
        return static::$appenders;
    }

    public static function exists(string $name): bool
    {
        return isset(static::$appenders[$name]);
    }

    public static function clear(): void
    {
         static::$appenders = [];
    }
}
