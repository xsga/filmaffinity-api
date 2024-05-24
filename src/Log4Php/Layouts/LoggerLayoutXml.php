<?php

namespace Log4Php\Layouts;

use Log4Php\LoggerLayout;
use Log4Php\LoggerLoggingEvent;

class LoggerLayoutXml extends LoggerLayout
{
    public const string LOG4J_NS_PREFIX = 'log4j';
    public const string LOG4J_NS = 'http://jakarta.apache.org/log4j/';
    public const string LOG4PHP_NS_PREFIX = 'log4php';
    public const string LOG4PHP_NS = 'http://logging.apache.org/log4php/';
    public const string CDATA_START = '<![CDATA[';
    public const string CDATA_END = ']]>';
    public const string CDATA_PSEUDO_END = ']]&gt;';
    public const string CDATA_EMBEDDED_END = ']]>]]&gt;<![CDATA[';

    protected bool $locationInfo = true;
    protected bool $log4jNamespace = false;
    protected string $namespace = self::LOG4PHP_NS;
    protected string $namespacePrefix = self::LOG4PHP_NS_PREFIX;

    public function activateOptions(): void
    {
        if ($this->getLog4jNamespace()) {
            $this->namespace        = static::LOG4J_NS;
            $this->namespacePrefix  = static::LOG4J_NS_PREFIX;

            return;
        }

        $this->namespace        = static::LOG4PHP_NS;
        $this->namespacePrefix  = static::LOG4PHP_NS_PREFIX;
    }

    public function getHeader(): string
    {
        $out  = '<' . $this->namespacePrefix . ':eventSet xmlns:';
        $out .= $this->namespacePrefix . '="' . $this->namespace . '" ';
        $out .= 'version="0.3" includesLocationInfo="' . ($this->getLocationInfo() ? 'true' : 'false') . '">';
        $out .= PHP_EOL;

        return $out;
    }

    public function format(LoggerLoggingEvent $event): string
    {
        $ns = $this->namespacePrefix;

        $loggerName = $event->getLoggerName();
        $timeStamp  = number_format(($event->getTimeStamp() * 1000), 0, '', '');
        $thread     = $event->getThreadName();
        $level      = $event->getLevel()->toString();

        $buf  = '<' . $ns . ':event logger="' . $loggerName . '" level="' . $level . '" thread="' . $thread . '" ';
        $buf .= 'timestamp="' . $timeStamp . '">' . PHP_EOL;
        $buf .= '<' . $ns . ':message>';
        $buf .= $this->encodeCDATA($event->getRenderedMessage());
        $buf .= '</' . $ns . ':message>' . PHP_EOL;

        $ndc = $event->getNDC();
        if (!empty($ndc)) {
            $buf .= '<' . $ns . ':NDC><![CDATA[';
            $buf .= $this->encodeCDATA($ndc);
            $buf .= ']]></' . $ns . ':NDC>' . PHP_EOL;
        }

        $mdcMap = $event->getMDCMap();
        if (!empty($mdcMap)) {
            $buf .= '<' . $ns . ':properties>' . PHP_EOL;
            foreach ($mdcMap as $name => $value) {
                $buf .= '<' . $ns . ':data name="' . $name . '" value="' . $value . '" />' . PHP_EOL;
            }
            $buf .= '</' . $ns . ':properties>' . PHP_EOL;
        }

        if ($this->getLocationInfo()) {
            $locationInfo = $event->getLocationInformation();
            $buf .= '<' . $ns . ':locationInfo class="' . $locationInfo->getClassName() . '" ';
            $buf .= 'file="' . htmlentities($locationInfo->getFileName(), ENT_QUOTES) . '" ';
            $buf .= 'line="' . $locationInfo->getLineNumber();
            $buf .= '" method="' . $locationInfo->getMethodName() . '"/>' . PHP_EOL;
        }

        $buf .= '</' . $ns . ':event>' . PHP_EOL;

        return $buf;
    }

    public function getFooter(): string
    {
        return '</' . $this->namespacePrefix . ':eventSet>' . PHP_EOL;
    }

    public function getLocationInfo(): bool
    {
        return $this->locationInfo;
    }

    public function setLocationInfo(bool $flag): void
    {
        $this->setBoolean('locationInfo', $flag);
    }

    public function getLog4jNamespace(): bool
    {
        return $this->log4jNamespace;
    }

    public function setLog4jNamespace(bool $flag): void
    {
        $this->setBoolean('log4jNamespace', $flag);
    }

    private function encodeCDATA(string $string): string
    {
        $string = str_replace(static::CDATA_END, static::CDATA_EMBEDDED_END, $string);

        return static::CDATA_START . $string . static::CDATA_END;
    }
}
