<?php

/**
 * LoggerPatternParser.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
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
 * @subpackage Helpers
 */

/**
 * Namespace.
 */
namespace Log4Php\Helpers;

/**
 * Import dependencies.
 */
use Log4Php\LoggerException;
use Log4Php\Pattern\LoggerPatternConverter;
use Log4Php\Pattern\LoggerPatternConverterLiteral;

/**
 * Most of the work of the {@link LoggerPatternLayout} class delegated to the {@link LoggerPatternParser} class.
 *
 * It is this class that parses conversion patterns and creates a chained list of LoggerPatternConverter converters.
 */
class LoggerPatternParser
{
    /**
     * Escape character for conversion words in the conversion pattern.
     *
     * @var string
     *
     * @access public
     */
    public const ESCAPE_CHAR = '%';

    /**
     * Maps conversion words to relevant converters.
     *
     * @var array
     *
     * @access private
     */
    private $converterMap;

    /**
     * Conversion pattern used in layout.
     *
     * @var string
     *
     * @access private
     */
    private $pattern;

    /**
     * Regex pattern used for parsing the conversion pattern.
     *
     * @var string
     *
     * @access private
     */
    private $regex;

    /**
     * First converter in the chain.
     *
     * @var LoggerPatternConverter|null
     *
     * @access private
     */
    private $head = null;

    /**
     * Last converter in the chain.
     *
     * @var LoggerPatternConverter|null
     *
     * @access private
     */
    private $tail = null;

    /**
     * Constructor.
     *
     * @param string $pattern      Pattern.
     * @param mixed  $converterMap Converter map.
     */
    public function __construct(string $pattern, mixed $converterMap)
    {
        $this->pattern      = $pattern;
        $this->converterMap = $converterMap;

        // Construct the regex pattern.
        $this->regex = '/' . static::ESCAPE_CHAR . '(?P<modifiers>[0-9.-]*)(?P<word>[a-zA-Z]+)(?P<option>{[^}]*})?/';
    }

    /**
     * Parses conversion pattern string, converts it to a chain of pattern converters, returns first converter in chain.
     *
     * @return LoggerPatternConverter|null
     *
     * @throws LoggerException
     *
     * @access public
     */
    public function parse(): LoggerPatternConverter|null
    {
        // Skip parsing if the pattern is empty.
        if (empty($this->pattern)) {
            $this->addLiteral('');
            return $this->head;
        }//end if

        // Find all conversion words in the conversion pattern.
        $count = preg_match_all($this->regex, $this->pattern, $matches, PREG_OFFSET_CAPTURE);

        if (!$count) {
            $error    = error_get_last();
            $errorMsg = isset($error['message']) ? $error['message'] : '';
            throw new LoggerException("Failed parsing layotut pattern: $errorMsg");
        }//end if

        $prevEnd = 0;
        $end     = 0;

        foreach ($matches[0] as $key => $item) {
            // Locate where the conversion command starts and ends.
            $length = strlen($item[0]);
            $start  = $item[1];
            $end    = ($item[1] + $length);

            // Find any literal expressions between matched commands.
            if ($start > $prevEnd) {
                $this->addLiteral(substr($this->pattern, $prevEnd, ($start - $prevEnd)));
            }//end if

            // Extract data.
            $word      = $this->extractData($matches, $key, 'word');
            $modifiers = $this->extractData($matches, $key, 'modifiers');
            $option    = $this->extractData($matches, $key, 'option');

            // Create a converter and add it to the chain.
            $this->addConverter($word, $modifiers, $option);

            $prevEnd = $end;
        }//end foreach

        // Add any trailing literals.
        if ($end < strlen($this->pattern)) {
            $this->addLiteral(substr($this->pattern, $end));
        }//end if

        return $this->head;
    }

    /**
     * Extract data from matches.
     *
     * @param array  $matches Matches.
     * @param mixed  $key     Key.
     * @param string $element Element.
     *
     * @return string
     *
     * @access private
     */
    private function extractData(array $matches, mixed $key, string $element): string
    {
        // Extract the data from the matched command.
        if (!empty($matches[$element][$key])) {
            return $matches[$element][$key][0];
        }//end if

        return '';
    }

    /**
     * Adds a literal converter to the converter chain.
     *
     * @param string $string The string for the literal converter.
     *
     * @return void
     *
     * @access private
     */
    private function addLiteral(string $string): void
    {
        $converter = new LoggerPatternConverterLiteral($string);
        $this->addToChain($converter);
    }

    /**
     * Adds a non-literal converter to the converter chain.
     *
     * @param string $word      The conversion word, used to determine which converter will be used.
     * @param string $modifiers Formatting modifiers.
     * @param string $option    Option to pass to the converter.
     *
     * @return void
     *
     * @access private
     */
    private function addConverter(string $word, string $modifiers, string $option): void
    {
        $formattingInfo = $this->parseModifiers($modifiers);
        $option         = trim($option, '{} ');

        if (isset($this->converterMap[$word])) {
            $this->addToChain($this->getConverter($word, $formattingInfo, $option));
            return;
        }//end if

        trigger_error('log4php: Invalid keyword "%$word" in converison pattern. Ignoring keyword.', E_USER_WARNING);
    }

    /**
     * Determines which converter to use based on the conversion word.
     *
     * Creates an instance of the converter using the provided formatting info and option and returns it.
     *
     * @param string               $word   The conversion word.
     * @param LoggerFormattingInfo $info   Formatting info.
     * @param string               $option Converter option.
     *
     * @throws LoggerException
     *
     * @return LoggerPatternConverter
     *
     * @access private
     */
    private function getConverter(string $word, LoggerFormattingInfo $info, string $option): LoggerPatternConverter
    {
        if (!isset($this->converterMap[$word])) {
            throw new LoggerException('Invalid keyword "%$word" in converison pattern. Ignoring keyword.');
        }//end if

        $converterClass = $this->converterMap[$word];

        $namespace      = 'Log4Php\\Pattern\\';
        $converterClass = $namespace . $converterClass;

        if (!class_exists($converterClass)) {
            throw new LoggerException('Class "' . $converterClass . '" does not exist.');
        }//end if

        $converter = new $converterClass($info, $option);

        if (!($converter instanceof LoggerPatternConverter)) {
            throw new LoggerException('Class "' . $converterClass . '" is not an instance of LoggerPatternConverter.');
        }//end if

        return $converter;
    }

    /**
     * Adds a converter to the chain and updates $head and $tail pointers.
     *
     * @param LoggerPatternConverter $converter Converter.
     *
     * @return void
     *
     * @access private
     */
    private function addToChain(LoggerPatternConverter $converter): void
    {
        if (is_null($this->head)) {
            $this->head = $converter;
            $this->tail = $this->head;
            return;
        }//end if

        $this->tail->next = $converter;
        $this->tail = $this->tail->next;
    }

    /**
     * Parses the formatting modifiers and produces the corresponding LoggerFormattingInfo object.
     *
     * @param string $modifiers Modifier.
     *
     * @return LoggerFormattingInfo
     *
     * @throws LoggerException
     *
     * @access private
     */
    private function parseModifiers(string $modifiers): LoggerFormattingInfo
    {
        $info = new LoggerFormattingInfo();

        // If no modifiers are given, return default values.
        if (empty($modifiers)) {
            return $info;
        }//end if

        // Validate.
        $pattern = '/^(-?[0-9]+)?\.?-?[0-9]+$/';

        if (preg_match($pattern, $modifiers) === false) {
            $log = "log4php: Invalid modifier in conversion pattern: [$modifiers]. Ignoring modifier.";
            trigger_error($log, E_USER_WARNING);
            return $info;
        }//end if

        $parts = explode('.', $modifiers);

        if (!empty($parts[0])) {
            $minPart       = (int)$parts[0];
            $info->min     = abs($minPart);

            if ($minPart > 0) {
                $info->padLeft = true;
            } else {
                $info->padLeft = false;
            }//end if
        }//end if

        if (!empty($parts[1])) {
            $maxPart        = (int)$parts[1];
            $info->max      = abs($maxPart);

            if ($maxPart < 0) {
                $info->trimLeft = true;
            } else {
                $info->trimLeft = false;
            }//end if
        }//end if

        return $info;
    }
}
