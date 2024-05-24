<?php

namespace Log4Php;

class LoggerLocationInfo
{
    public const string LOCATION_INFO_NA = 'NA';

    protected int $lineNumber;
    protected string $fileName;
    protected string $className;
    protected string $methodName;
    protected string $fullInfo = '';

    public function __construct(array $trace)
    {
        $this->lineNumber = $trace['line'] ?? 0;
        $this->fileName   = $trace['file'] ?? '';
        $this->className  = $trace['class'] ?? '';
        $this->methodName = $trace['function'] ?? '';

        $this->fullInfo  = $this->getClassName() . '.' . $this->getMethodName();
        $this->fullInfo .= '(' . $this->getFileName() . ':' . $this->getLineNumber() . ')';
    }

    public function getClassName(): string
    {
        if (empty($this->className)) {
            return static::LOCATION_INFO_NA;
        }

        return $this->className;
    }

    public function getFileName(): string
    {
        if (empty($this->fileName)) {
            return static::LOCATION_INFO_NA;
        }

        return $this->fileName;
    }

    public function getLineNumber(): int|string
    {
        if ($this->lineNumber === 0) {
            return static::LOCATION_INFO_NA;
        }

        return $this->lineNumber;
    }

    public function getMethodName(): string
    {
        if (empty($this->methodName)) {
            return static::LOCATION_INFO_NA;
        }

        return $this->methodName;
    }

    public function getFullInfo(): string
    {
        if (empty($this->fullInfo)) {
            return static::LOCATION_INFO_NA;
        }

        return $this->fullInfo;
    }
}
