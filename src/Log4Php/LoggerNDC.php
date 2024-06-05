<?php

namespace Log4Php;

class LoggerNDC
{
    private static array $stack = [];

    public static function clear(): void
    {
        static::$stack = [];
    }

    public static function get(): string
    {
        return implode(' ', static::$stack);
    }

    public static function getDepth(): int
    {
        return count(static::$stack);
    }

    public static function pop(): string
    {
        $count = (static::$stack);

        if ($count > 0) {
            return array_pop(static::$stack);
        }

        return '';
    }

    public static function peek(): string
    {
        $count = (static::$stack);

        if ($count > 0) {
            return end(static::$stack);
        }

        return '';
    }

    public static function push(string $message): void
    {
        array_push(static::$stack, $message);
    }

    public static function remove(): void
    {
        static::clear();
    }

    public static function setMaxDepth(int $maxDepth): void
    {
        if (static::getDepth() > $maxDepth) {
            static::$stack = array_slice(static::$stack, 0, $maxDepth);
        }
    }
}
