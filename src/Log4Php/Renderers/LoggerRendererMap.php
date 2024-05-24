<?php

namespace Log4Php\Renderers;

use Log4Php\Renderers\LoggerRenderer;

class LoggerRendererMap
{
    private array $map = [];
    private ?LoggerRenderer $defaultRenderer = null;

    public function __construct()
    {
        $this->reset();
    }

    public function addRenderer(string $renderedClass, string $renderingClass): void
    {
        $namespace = 'Log4Php\\Renderers\\';

        if (!class_exists($namespace . $renderingClass)) {
            trigger_error('log4php: Failed adding renderer. Rendering class [' . $renderingClass . '] not found.');
            return;
        }

        $class = $namespace . $renderingClass;

        $renderer = new $class();

        if (!($renderer instanceof LoggerRenderer)) {
            $msg  = 'log4php: Failed adding renderer. Rendering class [' . $renderingClass;
            $msg .= '] does not implement the LoggerRenderer interface.';

            trigger_error($msg);

            return;
        }

        $renderedClass = strtolower($renderedClass);

        $this->map[$renderedClass] = $renderer;
    }

    public function setDefaultRenderer(string $renderingClass): void
    {
        if (!class_exists($renderingClass)) {
            $log = 'log4php: Failed setting default renderer. Rendering class [' . $renderingClass . '] not found.';
            trigger_error($log);
            return;
        }

        $renderer = new $renderingClass();

        if (!($renderer instanceof LoggerRenderer)) {
            $msg  = 'log4php: Failed setting default renderer. Rendering class [' . $renderingClass;
            $msg .= '] does not implement the LoggerRenderer interface.';

            trigger_error($msg);

            return;
        }

        $this->defaultRenderer = $renderer;
    }

    public function getDefaultRenderer(): mixed
    {
        return $this->defaultRenderer;
    }

    public function findAndRender(mixed $input): ?string
    {
        if ($input === null) {
            return null;
        }

        if (is_object($input)) {
            $renderer = $this->getByClassName(get_class($input));

            if (isset($renderer)) {
                return $renderer->render($input);
            }
        }

        return $this->defaultRenderer->render($input);
    }

    public function getByObject(mixed $object): ?LoggerRenderer
    {
        if (!is_object($object)) {
            return null;
        }

        return $this->getByClassName(get_class($object));
    }

    public function getByClassName(string $class): ?LoggerRenderer
    {
        for (; !empty($class); $class = get_parent_class($class)) {
            $class = strtolower($class);

            if (isset($this->map[$class])) {
                return $this->map[$class];
            }
        }

        return null;
    }

    public function clear(): void
    {
        $this->map = [];
    }

    public function reset(): void
    {
        $this->defaultRenderer = new LoggerRendererDefault();
        $this->clear();
        $this->addRenderer('Exception', 'LoggerRendererException');
    }
}
