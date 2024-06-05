<?php

namespace Log4Php\Configurators;

use Log4Php\LoggerException;

class LoggerConfigurationAdapterPHP implements LoggerConfigurationAdapter
{
    public function convert(string $input): array
    {
        if (!file_exists($input)) {
            throw new LoggerException("File [$input] does not exist.");
        }

        $data = @file_get_contents($input);

        if (!$data) {
            $error = error_get_last();
            throw new LoggerException('Error loading config file: ' . $error['message']);
        }

        $config = @eval('' . $data);

        if (!$config) {
            $error = error_get_last();
            throw new LoggerException('Error parsing configuration: ' . $error['message']);
        }

        if (empty($config)) {
            throw new LoggerException('Invalid configuration: empty configuration array.');
        }

        if (!is_array($config)) {
            throw new LoggerException('Invalid configuration: not an array.');
        }

        return $config;
    }
}
