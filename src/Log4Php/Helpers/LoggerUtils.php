<?php

namespace Log4Php\Helpers;

class LoggerUtils
{
    public static function tokenizeClassName(string $name): array
    {
        $name      = str_replace('.', '\\', $name);
        $name      = trim($name, ' \\');
        $fragments = explode('\\', $name);

        foreach ($fragments as $key => $fragment) {
            if (trim($fragment) === '') {
                unset($fragments[$key]);
            }
        }

        return $fragments;
    }

    public static function shortenClassName(string $name, int $length): string
    {
        if ($length < 0) {
            return $name;
        }

        $name = str_replace('.', '\\', $name);
        $name = trim($name, ' \\');

        $currentLength = strlen($name);
        if ($currentLength <= $length) {
            return $name;
        }

        $fragments = explode('\\', $name);

        if ($length === 0) {
            return array_pop($fragments);
        }

        $count = count($fragments);
        if ($count === 1) {
            return $name;
        }

        foreach ($fragments as $key => &$fragment) {
            if ($key === ($count - 1)) {
                break;
            }

            $fragLen = strlen($fragment);
            if ($fragLen <= 1) {
                continue;
            }

            $fragment      = substr($fragment, 0, 1);
            $currentLength = ($currentLength - $fragLen + 1);

            if ($currentLength <= $length) {
                break;
            }
        }

        unset($fragment);

        return implode('\\', $fragments);
    }
}
