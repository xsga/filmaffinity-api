<?php

namespace Log4Php;

class LoggerMDC
{
    private static array $map = [];

    public static function put(string $key, string $value): void
    {
        static::$map[$key] = $value;
    }

    public static function get(string $key): string
    {
        if (isset(static::$map[$key])) {
            return static::$map[$key];
        }

        return '';
    }

    public static function getMap(): array
    {
        return static::$map;
    }

    public static function remove(string $key): void
    {
        unset(static::$map[$key]);
    }

    public static function clear(): void
    {
        static::$map = [];
    }
}
