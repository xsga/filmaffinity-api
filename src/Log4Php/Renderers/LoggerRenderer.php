<?php

namespace Log4Php\Renderers;

interface LoggerRenderer
{
    public function render(mixed $input): mixed;
}
