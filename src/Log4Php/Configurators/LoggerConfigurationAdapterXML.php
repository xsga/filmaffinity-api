<?php

/**
 * LoggerConfigurationAdapterXML.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP Version 8
 *
 * @package    Log4Php
 * @subpackage Configurators
 */

/**
 * Namespace.
 */
namespace Log4Php\Configurators;

/**
 * Import dependencies.
 */
use Log4Php\LoggerException;

/**
 * Converts XML configuration files to a PHP array.
 */
class LoggerConfigurationAdapterXML implements LoggerConfigurationAdapter
{
    /**
     * Path to the XML schema used for validation.
     *
     * @var string
     *
     * @access public
     */
    public const SCHEMA_PATH = '/../Xml/log4php.xsd';

    /**
     * Config.
     *
     * @var array
     *
     * @access private
     */
    private $config = array(
        'appenders' => [],
        'loggers'   => [],
        'renderers' => [],
    );

    /**
     * Convert.
     *
     * @param string $input Url.
     *
     * @return array
     *
     * @access public
     */
    public function convert($input): array
    {
        $xml = $this->loadXML($input);

        $this->parseConfiguration($xml);

        // Parse the <root> node.
        if (isset($xml->root)) {
            $this->parseRootLogger($xml->root);
        }//end if

        // Process <logger> nodes.
        foreach ($xml->logger as $logger) {
            $this->parseLogger($logger);
        }//end foreach

        // Process <appender> nodes.
        foreach ($xml->appender as $appender) {
            $this->parseAppender($appender);
        }//end foreach

        // Process <renderer> nodes.
        foreach ($xml->renderer as $rendererNode) {
            $this->parseRenderer($rendererNode);
        }//end foreach

        // Process <defaultRenderer> node.
        foreach ($xml->defaultRenderer as $rendererNode) {
            $this->parseDefaultRenderer($rendererNode);
        }//end foreach

        return $this->config;
    }

    /**
     * Loads and validates the XML.
     *
     * @param string $url Input XML.
     *
     * @return \SimpleXMLElement
     *
     * @access private
     *
     * @throws LoggerException
     */
    private function loadXML($url): \SimpleXMLElement
    {
        if (!file_exists($url)) {
            throw new LoggerException("File [$url] does not exist.");
        }//end if

        libxml_clear_errors();
        $oldValue = libxml_use_internal_errors(true);

        // Load XML.
        $xml = @simplexml_load_file($url);
        if (!$xml) {
            $errorStr = '';

            foreach (libxml_get_errors() as $error) {
                $errorStr .= $error->message;
            }//end foreach

            throw new LoggerException('Error loading configuration file: ' . trim($errorStr));
        }//end if

        libxml_clear_errors();
        libxml_use_internal_errors($oldValue);

        return $xml;
    }

    /**
     * Parses the <configuration> node.
     *
     * @param \SimpleXMLElement $xml XML.
     *
     * @return void
     *
     * @access private
     */
    private function parseConfiguration(\SimpleXMLElement $xml): void
    {
        $attributes = $xml->attributes();

        if (isset($attributes['threshold'])) {
            $this->config['threshold'] = (string)$attributes['threshold'];
        }//end if
    }

    /**
     * Parses an <appender> node.
     *
     * @param \SimpleXMLElement $node Node.
     *
     * @return void
     *
     * @access private
     */
    private function parseAppender(\SimpleXMLElement $node): void
    {
        $name = $this->getAttributeValue($node, 'name');

        if (empty($name)) {
            $log = 'An <appender> node is missing the required \'name\' attribute. Skipping appender definition.';
            $this->warn($log);
            return;
        }//end if

        $appender          = [];
        $appender['class'] = $this->getAttributeValue($node, 'class');

        if (isset($node['threshold'])) {
            $appender['threshold'] = $this->getAttributeValue($node, 'threshold');
        }//end if

        if (isset($node->layout)) {
            $appender['layout'] = $this->parseLayout($node->layout);
        }//end if

        if (count($node->param) > 0) {
            $appender['params'] = $this->parseParameters($node);
        }//end if

        foreach ($node->filter as $filterNode) {
            $appender['filters'][] = $this->parseFilter($filterNode);
        }//end foreach

        $this->config['appenders'][$name] = $appender;
    }

    /**
     * Parses a <layout> node.
     *
     * @param \SimpleXMLElement $node Node.
     *
     * @return array
     *
     * @access private
     */
    private function parseLayout(\SimpleXMLElement $node): array
    {
        $layout          = [];
        $layout['class'] = $this->getAttributeValue($node, 'class');

        if (count($node->param) > 0) {
            $layout['params'] = $this->parseParameters($node);
        }//end if

        return $layout;
    }

    /**
     * Parses any <param> child nodes returning them in an array.
     *
     * @param \SimpleXMLElement $paramsNode Parameters node.
     *
     * @return array
     *
     * @access private
     */
    private function parseParameters(\SimpleXMLElement $paramsNode): array
    {
        $params = [];

        foreach ($paramsNode->param as $paramNode) {
            if (empty($paramNode['name'])) {
                $this->warn('A <param> node is missing the required \'name\' attribute. Skipping parameter.');
                continue;
            }//end if

            $name  = $this->getAttributeValue($paramNode, 'name');
            $value = $this->getAttributeValue($paramNode, 'value');

            $params[$name] = $value;
        }//end foreach

        return $params;
    }

    /**
     * Parses a <root> node.
     *
     * @param \SimpleXMLElement $node Node.
     *
     * @return void
     *
     * @access private
     */
    private function parseRootLogger(\SimpleXMLElement $node): void
    {
        $logger = [];

        if (isset($node->level)) {
            $logger['level'] = $this->getAttributeValue($node->level, 'value');
        }//end if

        $logger['appenders'] = $this->parseAppenderReferences($node);

        $this->config['rootLogger'] = $logger;
    }

    /**
     * Parses a <logger> node.
     *
     * @param \SimpleXMLElement $node Node.
     *
     * @return void
     *
     * @access private
     */
    private function parseLogger(\SimpleXMLElement $node): void
    {
        $logger = [];

        $name = $this->getAttributeValue($node, 'name');

        if (empty($name)) {
            $this->warn('A <logger> node is missing the required \'name\' attribute. Skipping logger definition.');
            return;
        }//end if

        if (isset($node->level)) {
            $logger['level'] = $this->getAttributeValue($node->level, 'value');
        }//end if

        if (isset($node['additivity'])) {
            $logger['additivity'] = $this->getAttributeValue($node, 'additivity');
        }//end if

        $logger['appenders'] = $this->parseAppenderReferences($node);

        // Check for duplicate loggers.
        if (isset($this->config['loggers'][$name])) {
            $this->warn('Duplicate logger definition [' . $name . ']. Overwriting.');
        }//end if

        $this->config['loggers'][$name] = $logger;
    }

    /**
     * Parses a <logger> node for appender references and returns them in an array.
     *
     * Previous versions supported appender-ref, as well as appender_ref so both are parsed for backward compatibility.
     *
     * @param \SimpleXMLElement $node Node.
     *
     * @return array
     *
     * @access private
     */
    private function parseAppenderReferences(\SimpleXMLElement $node): array
    {
        $refs = [];

        foreach ($node->appender_ref as $ref) {
            $refs[] = $this->getAttributeValue($ref, 'ref');
        }//end foreach

        foreach ($node->{'appender-ref'} as $ref) {
            $refs[] = $this->getAttributeValue($ref, 'ref');
        }//end foreach

        return $refs;
    }

    /**
     * Parses a <filter> node.
     *
     * @param \SimpleXMLElement $filterNode Filter node.
     *
     * @return array
     *
     * @access private
     */
    private function parseFilter(\SimpleXMLElement $filterNode): array
    {
        $filter          = [];
        $filter['class'] = $this->getAttributeValue($filterNode, 'class');

        if (count($filterNode->param) > 0) {
            $filter['params'] = $this->parseParameters($filterNode);
        }//end if

        return $filter;
    }

    /**
     * Parses a <renderer> node.
     *
     * @param \SimpleXMLElement $node Node.
     *
     * @return void
     *
     * @access private
     */
    private function parseRenderer(\SimpleXMLElement $node): void
    {
        $renderedClass  = $this->getAttributeValue($node, 'renderedClass');
        $renderingClass = $this->getAttributeValue($node, 'renderingClass');

        $this->config['renderers'][] = compact('renderedClass', 'renderingClass');
    }

    /**
     * Parses a <defaultRenderer> node.
     *
     * @param \SimpleXMLElement $node Node.
     *
     * @return void
     *
     * @access private
     */
    private function parseDefaultRenderer(\SimpleXMLElement $node): void
    {
        $renderingClass = $this->getAttributeValue($node, 'renderingClass');

        // Warn on duplicates.
        if (isset($this->config['defaultRenderer'])) {
            $this->warn('Duplicate <defaultRenderer> node. Overwriting.');
        }//end if

        $this->config['defaultRenderer'] = $renderingClass;
    }

    /**
     * Get attribute value.
     *
     * @param \SimpleXMLElement $node Node.
     * @param string           $name Name.
     *
     * @return string|null
     *
     * @access private
     */
    private function getAttributeValue(\SimpleXMLElement $node, $name): string|null
    {
        if (isset($node[$name])) {
            return (string)$node[$name];
        }//end if

        return null;
    }

    /**
     * Warn.
     *
     * @param string $message Message.
     *
     * @return void
     *
     * @access private
     */
    private function warn($message): void
    {
        trigger_error("log4php: $message", E_USER_WARNING);
    }
}
