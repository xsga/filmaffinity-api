<?php

namespace Log4Php\Helpers;

use Log4Php\LoggerException;
use Log4Php\LoggerLevel;

class LoggerOptionConverter
{
    private static array $trueValues = ['1', 'true', 'yes', 'on'];
    private static array $falseValues = ['0', 'false', 'no', 'off', ''];

    public static function getSystemProperty(string $key, string $def): string
    {
        if (defined($key)) {
            return (string)constant($key);
        }

        if (isset($_SERVER[$key])) {
            return (string)$_SERVER[$key];
        }

        if (isset($_ENV[$key])) {
            return (string)$_ENV[$key];
        }

        return $def;
    }

    public static function toBooleanEx(mixed $value): bool
    {
        if (isset($value)) {
            if (is_bool($value)) {
                return $value;
            }

            $value = strtolower(trim($value));

            if (in_array($value, static::$trueValues)) {
                return true;
            }

            if (in_array($value, static::$falseValues)) {
                return false;
            }
        }

        throw new LoggerException('Given value [' . var_export($value, true) . '] cannot be converted to boolean.');
    }

    public static function toIntegerEx(mixed $value): int
    {
        if (is_integer($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        throw new LoggerException('Given value [' . var_export($value, true) . '] cannot be converted to integer.');
    }

    public static function toPositiveIntegerEx(mixed $value): int
    {
        if (is_integer($value) && ($value > 0)) {
            return $value;
        }

        if (is_numeric($value) && ($value > 0)) {
            return (int)$value;
        }

        $log = 'Given value [' . var_export($value, true) . '] cannot be converted to a positive integer.';

        throw new LoggerException($log);
    }

    public static function toLevelEx(mixed $value): LoggerLevel
    {
        if ($value instanceof LoggerLevel) {
            return $value;
        }

        $level = LoggerLevel::toLevel($value);

        if ($level === null) {
            $log = 'Given value [' . var_export($value, true) . '] cannot be converted to a logger level.';
            throw new LoggerException($log);
        }

        return $level;
    }

    public static function toFileSizeEx(mixed $value): int
    {
        if (empty($value)) {
            throw new LoggerException('Empty value cannot be converted to a file size.');
        }

        if (is_numeric($value)) {
            return (int)$value;
        }

        if (!is_string($value)) {
            $msg = 'Given value [' . var_export($value, true) . '] cannot be converted to a file size.';
            throw new LoggerException($msg);
        }

        $str   = strtoupper(trim($value));
        $count = preg_match('/^([0-9.]+)(KB|MB|GB)?$/', $str, $matches);

        if ($count > 0) {
            $size = (int)$matches[1];
            $unit = $matches[2];

            $size *= match ($unit) {
                'KB' => pow(1024, 1),
                'MB' => pow(1024, 2),
                'GB' => pow(1024, 3),
                default => 1
            };

            return (int)$size;
        }

        throw new LoggerException('Given value [' . $value . '] cannot be converted to a file size.');
    }

    public static function toStringEx(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (string)$value;
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return (string)$value;
        }

        throw new LoggerException('Given value [' . var_export($value, true) . '] cannot be converted to string.');
    }

    public static function substConstants(string $string): string
    {
        preg_match_all('/\${([^}]+)}/', $string, $matches);

        $replacement = '';

        foreach ($matches[1] as $key => $match) {
            $match  = trim($match);
            $search = $matches[0][$key];

            if (defined($match)) {
                $replacement = constant($match);
            } else {
                $replacement = '';
            }

            $string = str_replace($search, $replacement, $string);
        }

        return $string;
    }
}
