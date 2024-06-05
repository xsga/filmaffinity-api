<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\Layouts\LoggerLayoutSerialized;
use Log4Php\LoggerLoggingEvent;

class LoggerAppenderSocket extends LoggerAppender
{
    protected string $remoteHost = '';
    protected int $port = 4446;
    protected int $timeout = 0;

    public function getDefaultLayout(): LoggerLayoutSerialized
    {
        return new LoggerLayoutSerialized();
    }

    public function activateOptions(): void
    {
        if (empty($this->remoteHost)) {
            $this->warn('Required parameter [remoteHost] not set. Closing appender.');
            $this->closed = true;
            return;
        }

        if ($this->timeout === 0) {
            $this->timeout = (int)ini_get('default_socket_timeout');
        }

        $this->closed = false;
    }

    public function append(LoggerLoggingEvent $event): void
    {
        $socket = fsockopen($this->remoteHost, $this->port, $errno, $errstr, $this->timeout);

        if (!$socket) {
            $this->warn('Could not open socket to ' . $this->remoteHost . ':' . $this->port . '. Closing appender.');
            $this->warn("Error code: $errno ($errstr)");
            $this->closed = true;
            return;
        }

        if (fwrite($socket, $this->layout->format($event)) === false) {
            $this->warn('Error writing to socket. Closing appender.');
            $this->closed = true;
        }

        fclose($socket);
    }

    public function setRemoteHost(string $hostname): void
    {
        $this->setString('remoteHost', $hostname);
    }

    public function setPort(int $port): void
    {
        $this->setPositiveInteger('port', $port);
    }

    public function setTimeout(int $timeout): void
    {
        $this->setPositiveInteger('timeout', $timeout);
    }

    public function getRemoteHost(): string
    {
        return $this->getRemoteHost();
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }
}
