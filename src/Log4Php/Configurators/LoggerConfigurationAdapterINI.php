<?php

namespace Log4Php\Configurators;

use Log4Php\LoggerException;

class LoggerConfigurationAdapterINI implements LoggerConfigurationAdapter
{
    public const string ROOT_LOGGER_NAME   = 'root';
    public const string ADDITIVITY_PREFIX  = 'log4php.additivity.';
    public const string THRESHOLD_PREFIX   = 'log4php.threshold';
    public const string ROOT_LOGGER_PREFIX = 'log4php.rootLogger';
    public const string LOGGER_PREFIX      = 'log4php.logger.';
    public const string APPENDER_PREFIX    = 'log4php.appender.';
    public const string RENDERER_PREFIX    = 'log4php.renderer.';

    private array $config = [];

    private function load(string $url): array
    {
        if (!file_exists($url)) {
            throw new LoggerException("File [$url] does not exist.");
        }

        $properties = @parse_ini_file($url, true);

        if (!$properties) {
            $error    = error_get_last();
            $errorMsg = isset($error['message']) ? $error['message'] : '';
            throw new LoggerException("Error parsing configuration file: $errorMsg");
        }

        return $properties;
    }

    public function convert(string $input): array
    {
        $properties = $this->load($input);

        if (isset($properties[static::THRESHOLD_PREFIX])) {
            $this->config['threshold'] = $properties[static::THRESHOLD_PREFIX];
        }

        if (isset($properties[static::ROOT_LOGGER_PREFIX])) {
            $this->parseLogger($properties[static::ROOT_LOGGER_PREFIX], static::ROOT_LOGGER_NAME);
        }

        foreach ($properties as $key => $value) {
            if ($this->beginsWith($key, static::LOGGER_PREFIX)) {
                $name = substr($key, strlen(static::LOGGER_PREFIX));
                $this->parseLogger($value, $name);
            }

            if ($this->beginsWith($key, static::ADDITIVITY_PREFIX)) {
                $name = substr($key, strlen(static::ADDITIVITY_PREFIX));
                $this->config['loggers'][$name]['additivity'] = $value;
            } elseif ($this->beginsWith($key, static::APPENDER_PREFIX)) {
                $this->parseAppender($key, $value);
            } elseif ($this->beginsWith($key, static::RENDERER_PREFIX)) {
                $this->parseRenderer($key, $value);
            }
        }

        return $this->config;
    }

    private function parseLogger(string $value, string $name): void
    {
        $parts = explode(',', $value);

        if (empty($value)) {
            return;
        }

        $level = array_shift($parts);

        $appenders = [];

        while ($appender = array_shift($parts)) {
            $appender = trim($appender);
            if (!empty($appender)) {
                $appenders[] = trim($appender);
            }
        }

        if ($name === static::ROOT_LOGGER_NAME) {
            $this->config['rootLogger']['level']     = trim($level);
            $this->config['rootLogger']['appenders'] = $appenders;
            return;
        }

        $this->config['loggers'][$name]['level']     = trim($level);
        $this->config['loggers'][$name]['appenders'] = $appenders;
    }

    private function parseAppender(string $key, string $value): void
    {
        $subKey = substr($key, strlen(static::APPENDER_PREFIX));
        $parts  = explode('.', $subKey);
        $count  = count($parts);
        $name   = trim($parts[0]);

        if ($count === 1) {
            $this->config['appenders'][$name]['class'] = $value;
            return;
        }

        if ($count === 2) {
            if ($parts[1] === 'layout') {
                $this->config['appenders'][$name]['layout']['class'] = $value;
                return;
            }
            if ($parts[1] === 'threshold') {
                $this->config['appenders'][$name]['threshold'] = $value;
                return;
            }
            $this->config['appenders'][$name]['params'][$parts[1]] = $value;
            return;
        }

        if ($count === 3) {
            if ($parts[1] === 'layout') {
                $this->config['appenders'][$name]['layout']['params'][$parts[2]] = $value;
                return;
            }
        }

        trigger_error("log4php: Don't know how to parse the following line: '$key' = '$value'. Skipping.");
    }

    private function parseRenderer(string $key, string $value): void
    {
        $renderedClass  = substr($key, strlen(static::APPENDER_PREFIX));
        $renderingClass = $value;

        $this->config['renderers'][] = compact('renderedClass', 'renderingClass');
    }

    private function beginsWith(string $str, string $sub): bool
    {
        if (strncmp($str, $sub, strlen($sub)) === 0) {
            return true;
        }

        return false;
    }
}
