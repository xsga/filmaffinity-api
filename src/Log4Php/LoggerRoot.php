<?php

namespace Log4Php;

class LoggerRoot extends Logger
{
    public function __construct(?LoggerLevel $level = null)
    {
        parent::__construct('root');

        if ($level === null) {
            $level = LoggerLevel::getLevelAll();
        }

        $this->setLevel($level);
    }

    public function getEffectiveLevel(): LoggerLevel
    {
        return $this->getLevel();
    }

    public function setLevel(?LoggerLevel $level = null): void
    {
        if (isset($level)) {
            parent::setLevel($level);
            return;
        }

        trigger_error('log4php: Cannot set LoggerRoot level to null.', E_USER_WARNING);
    }

    public function setParent(Logger $logger): void
    {
        trigger_error('log4php: LoggerRoot cannot have a parent.', E_USER_WARNING);
    }
}
