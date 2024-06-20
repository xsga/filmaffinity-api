<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;

class LoggerAppenderMailEvent extends LoggerAppender
{
    protected string $from = '';
    protected int $port = 25;
    protected string $smtpHost = '';
    protected string $subject = 'Log4Php Report';
    protected ?string $to = null;

    public function activateOptions(): void
    {
        if (empty($this->to)) {
            $this->warn("Required parameter 'to' not set. Closing appender.");
            $this->closed = true;
            return;
        }

        $sendmailFrom = ini_get('sendmail_from');

        if (empty($this->from) && empty($sendmailFrom)) {
            $this->warn("Required parameter 'from' not set. Closing appender.");
            $this->closed = true;
            return;
        }

        $this->closed = false;
    }

    public function append(LoggerLoggingEvent $event): void
    {

        $smtpHost     = $this->smtpHost;
        $prevSmtpHost = ini_get('SMTP');

        if (!empty($smtpHost)) {
            ini_set('SMTP', $smtpHost);
        }

        $smtpPort     = $this->port;
        $prevSmtpPort = ini_get('smtp_port');

        if (($smtpPort > 0) && ($smtpPort < 65535)) {
            ini_set('smtp_port', (string)$smtpPort);
        }

        // On unix only sendmail_path, which is PHP_INI_SYSTEM i.e. not changeable here, is used.
        $addHeader = empty($this->from) ? '' : "From: {" . $this->from . "}\r\n";

        mail(
            $this->to,
            $this->subject,
            $this->layout->getHeader() . $this->layout->format($event) . $this->layout->getFooter(),
            $addHeader
        );

        ini_set('SMTP', $prevSmtpHost);
        ini_set('smtp_port', $prevSmtpPort);
    }

    public function setFrom(string $from): void
    {
        $this->setString('from', $from);
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function setPort(int $port): void
    {
        $this->setPositiveInteger('port', $port);
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setSmtpHost(string $smtpHost): void
    {
        $this->setString('smtpHost', $smtpHost);
    }

    public function getSmtpHost(): string
    {
        return $this->smtpHost;
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
        return $this->to ?? '';
    }

    public function setDry(bool $dry): void
    {
        $this->setBoolean('dry', $dry);
    }
}
