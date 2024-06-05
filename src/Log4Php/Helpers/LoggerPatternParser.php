<?php

namespace Log4Php\Helpers;

use Log4Php\LoggerException;
use Log4Php\Pattern\LoggerPatternConverter;
use Log4Php\Pattern\LoggerPatternConverterLiteral;

class LoggerPatternParser
{
    public const string ESCAPE_CHAR = '%';

    private array $converterMap;
    private string $pattern;
    private string $regex;
    private ?LoggerPatternConverter $head = null;
    private ?LoggerPatternConverter $tail = null;

    public function __construct(string $pattern, mixed $converterMap)
    {
        $this->pattern      = $pattern;
        $this->converterMap = $converterMap;

        $this->regex = '/' . static::ESCAPE_CHAR . '(?P<modifiers>[0-9.-]*)(?P<word>[a-zA-Z]+)(?P<option>{[^}]*})?/';
    }

    public function parse(): ?LoggerPatternConverter
    {
        if (empty($this->pattern)) {
            $this->addLiteral('');
            return $this->head;
        }

        $count = preg_match_all($this->regex, $this->pattern, $matches, PREG_OFFSET_CAPTURE);

        if (!$count) {
            $error    = error_get_last();
            $errorMsg = $error['message'] ?? '';
            throw new LoggerException("Failed parsing layotut pattern: $errorMsg");
        }

        $prevEnd = 0;
        $end     = 0;

        foreach ($matches[0] as $key => $item) {
            $length = strlen($item[0]);
            $start  = $item[1];
            $end    = ($item[1] + $length);

            if ($start > $prevEnd) {
                $this->addLiteral(substr($this->pattern, $prevEnd, ($start - $prevEnd)));
            }

            $word      = $this->extractData($matches, $key, 'word');
            $modifiers = $this->extractData($matches, $key, 'modifiers');
            $option    = $this->extractData($matches, $key, 'option');

            $this->addConverter($word, $modifiers, $option);

            $prevEnd = $end;
        }

        if ($end < strlen($this->pattern)) {
            $this->addLiteral(substr($this->pattern, $end));
        }

        return $this->head;
    }

    private function extractData(array $matches, mixed $key, string $element): string
    {
        if (!empty($matches[$element][$key])) {
            return $matches[$element][$key][0];
        }

        return '';
    }

    private function addLiteral(string $string): void
    {
        $converter = new LoggerPatternConverterLiteral($string);
        $this->addToChain($converter);
    }

    private function addConverter(string $word, string $modifiers, string $option): void
    {
        $formattingInfo = $this->parseModifiers($modifiers);
        $option         = trim($option, '{} ');

        if (isset($this->converterMap[$word])) {
            $this->addToChain($this->getConverter($word, $formattingInfo, $option));
            return;
        }

        trigger_error('log4php: Invalid keyword "%$word" in converison pattern. Ignoring keyword.', E_USER_WARNING);
    }

    private function getConverter(string $word, LoggerFormattingInfo $info, string $option): LoggerPatternConverter
    {
        if (!isset($this->converterMap[$word])) {
            throw new LoggerException('Invalid keyword "%$word" in converison pattern. Ignoring keyword.');
        }

        $converterClass = $this->converterMap[$word];

        $namespace      = 'Log4Php\\Pattern\\';
        $converterClass = $namespace . $converterClass;

        if (!class_exists($converterClass)) {
            throw new LoggerException('Class "' . $converterClass . '" does not exist.');
        }

        $converter = new $converterClass($info, $option);

        if (!($converter instanceof LoggerPatternConverter)) {
            throw new LoggerException('Class "' . $converterClass . '" is not an instance of LoggerPatternConverter.');
        }

        return $converter;
    }

    private function addToChain(LoggerPatternConverter $converter): void
    {
        if (is_null($this->head)) {
            $this->head = $converter;
            $this->tail = $this->head;
            return;
        }

        $this->tail->next = $converter;
        $this->tail = $this->tail->next;
    }

    private function parseModifiers(string $modifiers): LoggerFormattingInfo
    {
        $info = new LoggerFormattingInfo();

        if (empty($modifiers)) {
            return $info;
        }

        $pattern = '/^(-?[0-9]+)?\.?-?[0-9]+$/';

        if (preg_match($pattern, $modifiers) === false) {
            $log = "log4php: Invalid modifier in conversion pattern: [$modifiers]. Ignoring modifier.";
            trigger_error($log, E_USER_WARNING);
            return $info;
        }

        $parts = explode('.', $modifiers);

        if (!empty($parts[0])) {
            $minPart       = (int)$parts[0];
            $info->min     = abs($minPart);

            if ($minPart > 0) {
                $info->padLeft = true;
            } else {
                $info->padLeft = false;
            }
        }

        if (!empty($parts[1])) {
            $maxPart        = (int)$parts[1];
            $info->max      = abs($maxPart);

            if ($maxPart < 0) {
                $info->trimLeft = true;
            } else {
                $info->trimLeft = false;
            }
        }

        return $info;
    }
}
