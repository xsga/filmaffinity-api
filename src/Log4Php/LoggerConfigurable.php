<?php

namespace Log4Php;

use Exception;
use Log4Php\Helpers\LoggerOptionConverter;

abstract class LoggerConfigurable
{
    protected function setBoolean(string $property, bool $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toBooleanEx($value);
        } catch (Exception) {
            $this->warn($this->getErrorMsg($property, var_export($value, true), 'boolean'));
        }
    }

    protected function setInteger(string $property, string $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toIntegerEx($value);
        } catch (Exception) {
            $this->warn($this->getErrorMsg($property, var_export($value, true), 'integer'));
        }
    }

    protected function setLevel(string $property, mixed $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toLevelEx($value);
        } catch (Exception) {
            $this->warn($this->getErrorMsg($property, var_export($value, true), 'level'));
        }
    }

    protected function setPositiveInteger(string $property, mixed $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toPositiveIntegerEx($value);
        } catch (Exception) {
            $this->warn($this->getErrorMsg($property, var_export($value, true), 'positive integer'));
        }
    }

    protected function setFileSize(string $property, string $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toFileSizeEx($value);
        } catch (Exception) {
            $this->warn($this->getErrorMsg($property, var_export($value, true), 'file size'));
        }
    }

    protected function setNumeric(string $property, string $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toIntegerEx($value);
        } catch (Exception) {
            $this->warn($this->getErrorMsg($property, var_export($value, true), 'number'));
        }
    }

    protected function setString(string $property, string $value, bool $nullable = false): void
    {
        if (empty($value)) {
            if ($nullable) {
                $this->$property = null;
                return;
            }
            $this->warn($this->getErrorMsg($property, var_export($value, true), 'string'));
            return;
        }

        try {
            $value = LoggerOptionConverter::toStringEx($value);
            $this->$property = LoggerOptionConverter::substConstants($value);
        } catch (Exception) {
            $this->warn($this->getErrorMsg($property, var_export($value, true), 'string'));
        }
    }

    protected function warn(string $message): void
    {
        $class = get_class($this);
        trigger_error("log4php: $class : $message", E_USER_WARNING);
    }

    private function getErrorMsg(string $property, string $value, string $type): string
    {
         return "Invalid value given for '$property' property: [$value]. Expected $type value. Property not changed.";
    }
}
