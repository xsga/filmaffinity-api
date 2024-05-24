<?php

namespace Log4Php\Renderers;

class LoggerRendererDefault implements LoggerRenderer
{
    public function render(mixed $input): mixed
    {
        return print_r($input, true);
    }
}
