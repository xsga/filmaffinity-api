<?php

namespace Log4Php;

use Exception;
use Log4Php\Renderers\LoggerRendererException;

class LoggerThrowableInformation
{
    private array $throwableArray = [];

    public function __construct(private Exception $throwable)
    {
    }

    public function getThrowable(): Exception
    {
        return $this->throwable;
    }

    public function getStringRepresentation(): array
    {
        if (empty($this->throwableArray)) {
            $renderer = new LoggerRendererException();
            $this->throwableArray = explode("\n", $renderer->render($this->throwable));
        }

        return $this->throwableArray;
    }
}
