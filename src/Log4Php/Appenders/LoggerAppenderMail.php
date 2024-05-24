<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;

class LoggerAppenderMail extends LoggerAppender
{
    protected string $from = '';
    protected string $subject = 'Log4Php Report';
    protected string $to = '';
    protected string $body = '';

    public function append(LoggerLoggingEvent $event): void
    {
        if ($this->layout !== null) {
            $this->body .= $this->layout->format($event);
        }
    }

    public function close(): void
    {
        if (!$this->closed) {
            $from = $this->from;
            $to   = $this->to;

            if (!empty($this->body) && !empty($from) && !empty($to) && ($this->layout !== null)) {
                $subject = $this->subject;

                mail(
                    $to,
                    $subject,
                    $this->layout->getHeader() . $this->body . $this->layout->getFooter(),
                    "From: {" . $from . "}\r\n"
                );
            }

            $this->closed = true;
        }
    }

    public function setSubject(string $subject): void
    {
        $this->setString('subject', $subject);
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setTo(string $to): void
    {
        $this->setString('to', $to);
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function setFrom(string $from): void
    {
        $this->setString('from', $from);
    }

    public function getFrom(): string
    {
        return $this->from;
    }
}
