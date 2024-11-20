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
            $value = var_export($value, true);
            $msg = $this->getErrorMsg($property, $value) . "Expected a boolean value. Property not changed.";
            $this->warn($msg);
        }
    }

    protected function setInteger(string $property, string $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toIntegerEx($value);
        } catch (Exception) {
            $value = var_export($value, true);
            $msg = $this->getErrorMsg($property, $value) . "Expected an integer. Property not changed.";
            $this->warn($msg);
        }
    }

    protected function setLevel(string $property, string $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toLevelEx($value);
        } catch (Exception) {
            $value = var_export($value, true);
            $msg = $this->getErrorMsg($property, $value) . "Expected a level value. Property not changed.";
            $this->warn($msg);
        }
    }

    protected function setPositiveInteger(string $property, mixed $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toPositiveIntegerEx($value);
        } catch (Exception) {
            $value = var_export($value, true);
            $msg = $this->getErrorMsg($property, $value) . "Expected a positive integer. Property not changed.";
            $this->warn($msg);
        }
    }

    protected function setFileSize(string $property, string $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toFileSizeEx($value);
        } catch (Exception) {
            $value = var_export($value, true);
            $msg = $this->getErrorMsg($property, $value) . "Expected a file size value.  Property not changed.";
            $this->warn($msg);
        }
    }

    protected function setNumeric(string $property, string $value): void
    {
        try {
            $this->$property = LoggerOptionConverter::toIntegerEx($value);
        } catch (Exception) {
            $value = var_export($value, true);
            $msg = $this->getErrorMsg($property, $value) . "Expected a number. Property not changed.";
            $this->warn($msg);
        }
    }

    protected function setString(string $property, string $value, bool $nullable = false): void
    {
        if (empty($value)) {
            if ($nullable) {
                $this->$property = null;
                return;
            }
            $msg = "Null value given for '$property' property. Expected a string. Property not changed.";
            $this->warn($msg);
            return;
        }

        try {
            $value = LoggerOptionConverter::toStringEx($value);
            $this->$property = LoggerOptionConverter::substConstants($value);
        } catch (Exception) {
            $value = var_export($value, true);
            $msg = $this->getErrorMsg($property, $value) . "Expected a string. Property not changed.";
            $this->warn($msg);
        }
    }

    protected function warn(string $message): void
    {
        $class = get_class($this);
        trigger_error("log4php: $class : $message", E_USER_WARNING);
    }

    private function getErrorMsg(string $property, string $value): string
    {
         return "Invalid value given for '$property' property: [$value]. ";
    }
}
