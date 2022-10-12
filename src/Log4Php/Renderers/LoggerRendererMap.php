<?php

/**
 * LoggerRendererMap.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP Version 8
 *
 * @package Log4Php
 */

/**
 * Namespace.
 */
namespace Log4Php\Renderers;

/**
 * Import dependencies.
 */
use Log4Php\Renderers\LoggerRenderer;

/**
 * Manages defined renderers and determines which renderer to use for a given input.
 */
class LoggerRendererMap
{
    /**
     * Maps class names to appropriate renderers.
     *
     * @var array
     *
     * @access private
     */
    private $map = array();

    /**
     * The default renderer to use if no specific renderer is found.
     *
     * @var LoggerRenderer|null
     *
     * @access private
     */
    private $defaultRenderer = null;

    /**
     * Constructor.
     *
     * @access public
     */
    public function __construct()
    {
        // Set default config.
        $this->reset();
    }

    /**
     * Adds a renderer to the map. If a renderer already exists it will be overwritten without warning.
     *
     * @param string $renderedClass  The name of the class which will be rendered by the renderer.
     * @param string $renderingClass The name of the class which will perform the rendering.
     *
     * @return void
     *
     * @access public
     */
    public function addRenderer(string $renderedClass, string $renderingClass): void
    {
        $namespace = 'Log4Php\\Renderers\\';

        // Check the rendering class exists.
        if (!class_exists($namespace . $renderingClass)) {
            trigger_error('log4php: Failed adding renderer. Rendering class [' . $renderingClass . '] not found.');
            return;
        }//end if

        $class = $namespace . $renderingClass;

        // Create the instance.
        $renderer = new $class();

        // Check the class implements the right interface.
        if (!($renderer instanceof LoggerRenderer)) {
            $msg  = 'log4php: Failed adding renderer. Rendering class [' . $renderingClass;
            $msg .= '] does not implement the LoggerRenderer interface.';

            trigger_error($msg);

            return;
        }//end if

        // Convert to lowercase since class names in PHP are not case sensitive.
        $renderedClass = strtolower($renderedClass);

        $this->map[$renderedClass] = $renderer;
    }

    /**
     * Sets a custom default renderer class.
     *
     * TODO: there's code duplication here. This method is almost identical to addRenderer().
     *
     * @param string $renderingClass The name of the class which will perform the rendering.
     *
     * @return void
     *
     * @access public
     */
    public function setDefaultRenderer(string $renderingClass): void
    {
        // Check the class exists.
        if (!class_exists($renderingClass)) {
            $log = 'log4php: Failed setting default renderer. Rendering class [' . $renderingClass . '] not found.';
            trigger_error($log);
            return;
        }//end if

        // Create the instance.
        $renderer = new $renderingClass();

        // Check the class implements the right interface.
        if (!($renderer instanceof LoggerRenderer)) {
            $msg  = 'log4php: Failed setting default renderer. Rendering class [' . $renderingClass;
            $msg .= '] does not implement the LoggerRenderer interface.';

            trigger_error($msg);

            return;
        }//end if

        $this->defaultRenderer = $renderer;
    }

    /**
     * Returns the default renderer.
     *
     * @return mixed
     *
     * @access public
     */
    public function getDefaultRenderer(): mixed
    {
        return $this->defaultRenderer;
    }

    /**
     * Finds the appropriate renderer for the given input, and renders it (i.e. converts it to a string).
     *
     * @param mixed $input Input to render.
     *
     * @return string|null
     *
     * @access public
     */
    public function findAndRender(mixed $input): string|null
    {
        if ($input === null) {
            return null;
        }//end if

        // For objects, try to find a renderer in the map.
        if (is_object($input)) {
            $renderer = $this->getByClassName(get_class($input));

            if (isset($renderer)) {
                return $renderer->render($input);
            }//end if
        }//end if

        // Fall back to the default renderer.
        return $this->defaultRenderer->render($input);
    }

    /**
     * Returns the appropriate renderer for a given object.
     *
     * @param mixed $object Object.
     *
     * @return LoggerRenderer|null
     *
     * @access public
     */
    public function getByObject(mixed $object): LoggerRenderer|null
    {
        if (!is_object($object)) {
            return null;
        }//end if

        return $this->getByClassName(get_class($object));
    }

    /**
     * Returns the appropriate renderer for a given class name. If no renderer could be found, returns null.
     *
     * @param string $class Clas.
     *
     * @return LoggerRenderer|null
     *
     * @access public
     */
    public function getByClassName(string $class): LoggerRenderer|null
    {
        for (; !empty($class); $class = get_parent_class($class)) {
            $class = strtolower($class);

            if (isset($this->map[$class])) {
                return $this->map[$class];
            }//end if
        }//end for

        return null;
    }

    /**
     * Empties the renderer map.
     *
     * @return void
     *
     * @access public
     */
    public function clear(): void
    {
        $this->map = array();
    }

    /**
     * Resets the renderer map to it's default configuration.
     *
     * @return void
     *
     * @access public
     */
    public function reset(): void
    {
        $this->defaultRenderer = new LoggerRendererDefault();
        $this->clear();
        $this->addRenderer('Exception', 'LoggerRendererException');
    }
}
