<?php

/**
 * LoggerAppender.
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
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
namespace Log4Php;

/**
 * Import dependencies.
 */
use Log4Php\Layouts\LoggerLayoutSimple;

/**
 * Abstract class that defines output logs strategies.
 */
abstract class LoggerAppender extends LoggerConfigurable
{
    /**
     * Set to true when the appender is closed. A closed appender will not accept any logging requests.
     *
     * @var boolean
     *
     * @access protected
     */
    protected $closed = false;

    /**
     * The first filter in the filter chain.
     *
     * @var LoggerFilter|null
     *
     * @access protected
     */
    protected $filter = null;

    /**
     * The appender's layout. Can be null if the appender does not use a layout.
     *
     * @var LoggerLayout|null
     *
     * @access protected
     */
    protected $layout = null;

    /**
     * Appender name. Used by other components to identify this appender.
     *
     * @var string
     *
     * @access protected
     */
    protected $name;

    /**
     * Appender threshold level. Events whose level is below the threshold will not be logged.
     *
     * @var LoggerLevel|null
     *
     * @access protected
     */
    protected $threshold = null;

    /**
     * Set to true if the appender requires a layout.
     *
     * @var boolean
     *
     * @access protected
     */
    protected $requiresLayout = true;

    /**
     * Default constructor.
     *
     * @param string $name Appender name.
     *
     * @access public
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;

        if ($this->requiresLayout) {
            $this->layout = $this->getDefaultLayout();
        }//end if
    }

    /**
     * Destructor.
     *
     * @access public
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Returns the default layout for this appender. Can be overriden by derived appenders.
     *
     * @return LoggerLayout
     *
     * @access public
     */
    public function getDefaultLayout(): LoggerLayout
    {
        return new LoggerLayoutSimple();
    }

    /**
     * Adds a filter to the end of the filter chain.
     *
     * @param LoggerFilter $filter Add a new LoggerFilter.
     *
     * @return void
     *
     * @access public
     */
    public function addFilter(LoggerFilter $filter): void
    {
        if ($this->filter === null) {
            $this->filter = $filter;
            return;
        }//end if

        $this->filter->addNext($filter);
    }

    /**
     * Clears the filter chain by removing all the filters in it.
     *
     * @return void
     *
     * @access public
     */
    public function clearFilters(): void
    {
        $this->filter = null;
    }

    /**
     * Returns the first filter in the filter chain. The return value may be <i>null</i> if no is filter is set.
     *
     * @return LoggerFilter|null
     *
     * @access public
     */
    public function getFilter(): LoggerFilter|null
    {
        return $this->filter;
    }

    /**
     * Returns the first filter in the filter chain. The return value may be <i>null</i> if no is filter is set.
     *
     * @return LoggerFilter|null
     *
     * @access public
     */
    public function getFirstFilter(): LoggerFilter|null
    {
        return $this->filter;
    }

    /**
     * Performs threshold checks and invokes filters before delegating logging to the subclass specific append() method.
     *
     * @param LoggerLoggingEvent $event Event.
     *
     * @see    LoggerAppender::append()
     *
     * @return void
     *
     * @access public
     */
    public function doAppend(LoggerLoggingEvent $event): void
    {
        if ($this->closed) {
            return;
        }//end if

        if (!$this->isAsSevereAsThreshold($event->getLevel())) {
            return;
        }//end if

        $filter = $this->getFirstFilter();

        while ($filter !== null) {
            switch ($filter->decide($event)) {
                case LoggerFilter::DENY:
                    return;

                case LoggerFilter::ACCEPT:
                    $this->append($event);
                    return;

                case LoggerFilter::NEUTRAL:
                    $filter = $filter->getNext();
                    return;

                default:
                    break;
            }//end switch
        }//end while

        $this->append($event);
    }

    /**
     * Sets the appender layout.
     *
     * @param LoggerLayout $layout Layout.
     *
     * @return void
     *
     * @access public
     */
    public function setLayout(LoggerLayout $layout): void
    {
        if ($this->requiresLayout()) {
            $this->layout = $layout;
        }//end if
    }

    /**
     * Returns the appender layout.
     *
     * @return LoggerLayout|null
     *
     * @access public
     */
    public function getLayout(): LoggerLayout|null
    {
        return $this->layout;
    }

    /**
     * Configurators call this method to determine if the appender requires a layout.
     *
     * If this method returns <i>true</i>, meaning that layout is required,
     * then the configurator will configure a layout using the configuration
     * information at its disposal.     If this method returns <i>false</i>,
     * meaning that a layout is not required, then layout configuration will be
     * skipped even if there is available layout configuration
     * information at the disposal of the configurator.
     *
     * In the rather exceptional case, where the appender
     * implementation admits a layout but can also work without it, then
     * the appender should return <i>true</i>.
     *
     * @return boolean
     *
     * @access public
     */
    public function requiresLayout(): bool
    {
        return $this->requiresLayout;
    }

    /**
     * Retruns the appender name.
     *
     * @return string
     *
     * @access public
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the appender name.
     *
     * @param string $name Name.
     *
     * @return void
     *
     * @access public
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns the appender's threshold level.
     *
     * @return LoggerLevel|null
     *
     * @access public
     */
    public function getThreshold(): LoggerLevel|null
    {
        return $this->threshold;
    }

    /**
     * Sets the appender threshold.
     *
     * @param LoggerLevel|string $threshold Either a {@link LoggerLevel} object or a string equivalent.
     *
     * @return void
     *
     * @access public
     */
    public function setThreshold(LoggerLevel|string $threshold): void
    {
        $this->setLevel('threshold', (string)$threshold);
    }

    /**
     * Checks whether the message level is below the appender's threshold.
     *
     * If there is no threshold set, then the return value is always <i>true</i>.
     *
     * @param LoggerLevel $level Error level.
     *
     * @return boolean Returns true if level is greater or equal than threshold, or if the threshold is not set.
     *
     * @access public
     */
    public function isAsSevereAsThreshold(LoggerLevel $level): bool
    {
        if ($this->threshold === null) {
            return true;
        }//end if

        return $level->isGreaterOrEqual($this->getThreshold());
    }

    /**
     * Prepares the appender for logging.
     *
     * Derived appenders should override this method if option structure requires it.
     *
     * @return void
     *
     * @access public
     */
    public function activateOptions(): void
    {
        $this->closed = false;
    }

    /**
     * Forwards the logging event to the destination.
     *
     * Derived appenders should implement this method to perform actual logging.
     *
     * @param LoggerLoggingEvent $event Logging event.
     *
     * @return void
     *
     * @access protected
     */
    abstract protected function append(LoggerLoggingEvent $event): void;

    /**
     * Releases any resources allocated by the appender.
     *
     * Derived appenders should override this method to perform proper closing procedures.
     *
     * @return void
     *
     * @access public
     */
    public function close(): void
    {
        $this->closed = true;
    }

    /**
     * Triggers a warning for this logger with the given message.
     *
     * @param mixed $message Message.
     *
     * @return void
     *
     * @access protected
     */
    protected function warn(mixed $message): void
    {
        $id = get_class($this) . (empty($this->name) ? '' : ':' . $this->name);
        trigger_error("log4php: [$id]: $message", E_USER_WARNING);
    }
}
