<?php

namespace Log4Php\Configurators;

interface LoggerConfigurationAdapter
{
    public function convert(string $input): array;
}
