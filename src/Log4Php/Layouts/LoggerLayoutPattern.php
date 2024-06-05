<?php

namespace Log4Php\Layouts;

use Log4Php\LoggerLayout;
use Log4Php\LoggerException;
use Log4Php\Helpers\LoggerPatternParser;
use Log4Php\LoggerLoggingEvent;
use Log4Php\Pattern\LoggerPatternConverter;

class LoggerLayoutPattern extends LoggerLayout
{
    public const string DEFAULT_CONVERSION_PATTERN = '%date %-5level %logger %message%newline';
    public const string TTCC_CONVERSION_PATTERN = '%d [%t] %p %c %x - %m%n';

    protected string $pattern = self::DEFAULT_CONVERSION_PATTERN;
    protected static array $defaultConverterMap = [
        'c'         => 'LoggerPatternConverterLogger',
        'lo'        => 'LoggerPatternConverterLogger',
        'logger'    => 'LoggerPatternConverterLogger',
        'C'         => 'LoggerPatternConverterClass',
        'class'     => 'LoggerPatternConverterClass',
        'cookie'    => 'LoggerPatternConverterCookie',
        'd'         => 'LoggerPatternConverterDate',
        'date'      => 'LoggerPatternConverterDate',
        'e'         => 'LoggerPatternConverterEnvironment',
        'env'       => 'LoggerPatternConverterEnvironment',
        'ex'        => 'LoggerPatternConverterThrowable',
        'exception' => 'LoggerPatternConverterThrowable',
        'throwable' => 'LoggerPatternConverterThrowable',
        'F'         => 'LoggerPatternConverterFile',
        'file'      => 'LoggerPatternConverterFile',
        'l'         => 'LoggerPatternConverterLocation',
        'location'  => 'LoggerPatternConverterLocation',
        'L'         => 'LoggerPatternConverterLine',
        'line'      => 'LoggerPatternConverterLine',
        'm'         => 'LoggerPatternConverterMessage',
        'msg'       => 'LoggerPatternConverterMessage',
        'message'   => 'LoggerPatternConverterMessage',
        'M'         => 'LoggerPatternConverterMethod',
        'method'    => 'LoggerPatternConverterMethod',
        'n'         => 'LoggerPatternConverterNewLine',
        'newline'   => 'LoggerPatternConverterNewLine',
        'p'         => 'LoggerPatternConverterLevel',
        'le'        => 'LoggerPatternConverterLevel',
        'level'     => 'LoggerPatternConverterLevel',
        'r'         => 'LoggerPatternConverterRelative',
        'relative'  => 'LoggerPatternConverterRelative',
        'req'       => 'LoggerPatternConverterRequest',
        'request'   => 'LoggerPatternConverterRequest',
        's'         => 'LoggerPatternConverterServer',
        'server'    => 'LoggerPatternConverterServer',
        'ses'       => 'LoggerPatternConverterSession',
        'session'   => 'LoggerPatternConverterSession',
        'sid'       => 'LoggerPatternConverterSessionID',
        'sessionid' => 'LoggerPatternConverterSessionID',
        't'         => 'LoggerPatternConverterProcess',
        'pid'       => 'LoggerPatternConverterProcess',
        'process'   => 'LoggerPatternConverterProcess',
        'x'         => 'LoggerPatternConverterNDC',
        'ndc'       => 'LoggerPatternConverterNDC',
        'X'         => 'LoggerPatternConverterMDC',
        'mdc'       => 'LoggerPatternConverterMDC',
    ];
    protected array $converterMap = [];
    private ?LoggerPatternConverter $head = null;

    public static function getDefaultConverterMap(): array
    {
        return static::$defaultConverterMap;
    }

    public function __construct()
    {
        $this->converterMap = static::$defaultConverterMap;
    }

    public function setConversionPattern(string $conversionPattern): void
    {
        $this->pattern = $conversionPattern;
    }

    public function activateOptions(): void
    {
        if (empty($this->pattern)) {
            throw new LoggerException("Mandatory parameter 'conversionPattern' is not set.");
        }

        $parser = new LoggerPatternParser($this->pattern, $this->converterMap);

        $this->head = $parser->parse();
    }

    public function format(LoggerLoggingEvent $event): string
    {
        $sbuf = '';
        $converter = $this->head;
        while ($converter !== null) {
            $converter->format($sbuf, $event);
            $converter = $converter->next;
        }

        return $sbuf;
    }
}
