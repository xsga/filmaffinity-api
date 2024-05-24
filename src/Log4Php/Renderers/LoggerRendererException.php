<?php

namespace Log4Php\Renderers;

class LoggerRendererException implements LoggerRenderer
{
    public function render(mixed $input): string
    {
        return (string)$input;
    }
}
