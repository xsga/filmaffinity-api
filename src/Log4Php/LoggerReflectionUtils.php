<?php

namespace Log4Php;

use RuntimeException;

class LoggerReflectionUtils
{
    public function __construct(private object $obj)
    {
    }

    public static function setPropertiesByObject(object $obj, array $properties, string $prefix): void
    {
        $pSetter = new LoggerReflectionUtils($obj);
        $pSetter->setProperties($properties, $prefix);
    }

    public function setProperties(array $properties, string $prefix): void
    {
        $len = strlen($prefix);

        reset($properties);

        foreach ($properties as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                if (strpos($key, '.', ($len + 1)) > 0) {
                    continue;
                }

                $value = $properties[$key];
                $key   = substr($key, $len);

                if ($key === 'layout' && $this->obj instanceof LoggerAppender) {
                    continue;
                }

                $this->setProperty($key, $value);
            }
        }

        $this->activate();
    }

    public function setProperty(string $name, string $value): mixed
    {
        if (empty($value)) {
            return null;
        }

        $method = 'set' . ucfirst($name);

        if (!method_exists($this->obj, $method)) {
            $class = get_class($this->obj);
            $msg = "Error setting log4php property $name to $value: no method $method in class $class";
            throw new RuntimeException($msg);
        }

        return call_user_func([$this->obj, $method], $value);
    }

    public function activate(): mixed
    {
        if (method_exists($this->obj, 'activateoptions')) {
            return call_user_func([$this->obj, 'activateoptions']);
        }

        return null;
    }

    public static function createObject(string $class): mixed
    {
        if (!empty($class)) {
            return new $class();
        }

        return null;
    }

    public static function setter(object $object, string $name, mixed $value): mixed
    {
        if (empty($name)) {
            return false;
        }

        $methodName = 'set' . ucfirst($name);

        if (method_exists($object, $methodName)) {
            return call_user_func([$object, $methodName], $value);
        }

        return false;
    }
}
