<?php

namespace Log4Php;

interface LoggerConfigurator
{
    public function configure(LoggerHierarchy $hierarchy, string|array|null $input = null): void;
}
