<?php

namespace Log4Php\Configurators;

use Log4Php\LoggerException;
use SimpleXMLElement;

class LoggerConfigurationAdapterXML implements LoggerConfigurationAdapter
{
    public const string SCHEMA_PATH = '/../Xml/log4php.xsd';

    private array $config = [
        'appenders' => [],
        'loggers'   => [],
        'renderers' => [],
    ];

    public function convert(string $input): array
    {
        $xml = $this->loadXML($input);

        $this->parseConfiguration($xml);

        if (isset($xml->root)) {
            $this->parseRootLogger($xml->root);
        }

        foreach ($xml->logger as $logger) {
            $this->parseLogger($logger);
        }

        foreach ($xml->appender as $appender) {
            $this->parseAppender($appender);
        }

        foreach ($xml->renderer as $rendererNode) {
            $this->parseRenderer($rendererNode);
        }

        foreach ($xml->defaultRenderer as $rendererNode) {
            $this->parseDefaultRenderer($rendererNode);
        }

        return $this->config;
    }

    private function loadXML(string $url): SimpleXMLElement
    {
        if (!file_exists($url)) {
            throw new LoggerException("File [$url] does not exist.");
        }

        libxml_clear_errors();
        $oldValue = libxml_use_internal_errors(true);

        $xml = @simplexml_load_file($url);
        if (!$xml) {
            $errorStr = '';
            foreach (libxml_get_errors() as $error) {
                $errorStr .= $error->message;
            }
            throw new LoggerException('Error loading configuration file: ' . trim($errorStr));
        }

        libxml_clear_errors();
        libxml_use_internal_errors($oldValue);

        return $xml;
    }

    private function parseConfiguration(SimpleXMLElement $xml): void
    {
        $attributes = $xml->attributes();

        if (isset($attributes['threshold'])) {
            $this->config['threshold'] = (string)$attributes['threshold'];
        }
    }

    private function parseAppender(SimpleXMLElement $node): void
    {
        $name = $this->getAttributeValue($node, 'name');

        if (empty($name)) {
            $log = "An <appender> node is missing the required 'name' attribute. Skipping appender definition.";
            $this->warn($log);
            return;
        }

        $appender          = [];
        $appender['class'] = $this->getAttributeValue($node, 'class');

        if (isset($node['threshold'])) {
            $appender['threshold'] = $this->getAttributeValue($node, 'threshold');
        }

        if (isset($node->layout)) {
            $appender['layout'] = $this->parseLayout($node->layout);
        }

        if (count($node->param) > 0) {
            $appender['params'] = $this->parseParameters($node);
        }

        foreach ($node->filter as $filterNode) {
            $appender['filters'][] = $this->parseFilter($filterNode);
        }

        $this->config['appenders'][$name] = $appender;
    }

    private function parseLayout(SimpleXMLElement $node): array
    {
        $layout          = [];
        $layout['class'] = $this->getAttributeValue($node, 'class');

        if (count($node->param) > 0) {
            $layout['params'] = $this->parseParameters($node);
        }

        return $layout;
    }

    private function parseParameters(SimpleXMLElement $paramsNode): array
    {
        $params = [];

        foreach ($paramsNode->param as $paramNode) {
            if (empty($paramNode['name'])) {
                $this->warn('A <param> node is missing the required \'name\' attribute. Skipping parameter.');
                continue;
            }

            $name  = $this->getAttributeValue($paramNode, 'name');
            $value = $this->getAttributeValue($paramNode, 'value');

            $params[$name] = $value;
        }

        return $params;
    }

    private function parseRootLogger(SimpleXMLElement $node): void
    {
        $logger = [];

        if (isset($node->level)) {
            $logger['level'] = $this->getAttributeValue($node->level, 'value');
        }

        $logger['appenders'] = $this->parseAppenderReferences($node);

        $this->config['rootLogger'] = $logger;
    }

    private function parseLogger(SimpleXMLElement $node): void
    {
        $logger = [];

        $name = $this->getAttributeValue($node, 'name');

        if (empty($name)) {
            $this->warn('A <logger> node is missing the required \'name\' attribute. Skipping logger definition.');
            return;
        }

        if (isset($node->level)) {
            $logger['level'] = $this->getAttributeValue($node->level, 'value');
        }

        if (isset($node['additivity'])) {
            $logger['additivity'] = $this->getAttributeValue($node, 'additivity');
        }

        $logger['appenders'] = $this->parseAppenderReferences($node);

        if (isset($this->config['loggers'][$name])) {
            $this->warn('Duplicate logger definition [' . $name . ']. Overwriting.');
        }

        $this->config['loggers'][$name] = $logger;
    }

    private function parseAppenderReferences(SimpleXMLElement $node): array
    {
        $refs = [];

        foreach ($node->appender_ref as $ref) {
            $refs[] = $this->getAttributeValue($ref, 'ref');
        }

        foreach ($node->{'appender-ref'} as $ref) {
            $refs[] = $this->getAttributeValue($ref, 'ref');
        }

        return $refs;
    }

    private function parseFilter(SimpleXMLElement $filterNode): array
    {
        $filter          = [];
        $filter['class'] = $this->getAttributeValue($filterNode, 'class');

        if (count($filterNode->param) > 0) {
            $filter['params'] = $this->parseParameters($filterNode);
        }

        return $filter;
    }

    private function parseRenderer(SimpleXMLElement $node): void
    {
        $renderedClass  = $this->getAttributeValue($node, 'renderedClass');
        $renderingClass = $this->getAttributeValue($node, 'renderingClass');

        $this->config['renderers'][] = compact('renderedClass', 'renderingClass');
    }

    private function parseDefaultRenderer(SimpleXMLElement $node): void
    {
        $renderingClass = $this->getAttributeValue($node, 'renderingClass');

        if (isset($this->config['defaultRenderer'])) {
            $this->warn('Duplicate <defaultRenderer> node. Overwriting.');
        }

        $this->config['defaultRenderer'] = $renderingClass;
    }

    private function getAttributeValue(SimpleXMLElement $node, string $name): ?string
    {
        if (isset($node[$name])) {
            return (string)$node[$name];
        }

        return null;
    }

    private function warn(string $message): void
    {
        trigger_error("log4php: $message", E_USER_WARNING);
    }
}
