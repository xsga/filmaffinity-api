<?php

namespace Log4Php\Layouts;

use DateTime;
use Log4Php\LoggerLayout;
use Log4Php\LoggerLoggingEvent;
use Log4Php\LoggerLevel;

class LoggerLayoutHtml extends LoggerLayout
{
    protected bool $locationInfo = false;
    protected string $title = 'Log4Php Log Messages';

    public function setLocationInfo(bool $flag): void
    {
        $this->setBoolean('locationInfo', $flag);
    }

    public function getLocationInfo(): bool
    {
        return $this->locationInfo;
    }

    public function setTitle(string $title): void
    {
        $this->setString('title', $title);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContentType(): string
    {
        return 'text/html';
    }

    public function format(LoggerLoggingEvent $event): string
    {
        $sbuf  = PHP_EOL . '<tr>' . PHP_EOL;
        $sbuf .= '<td>';
        $sbuf .= round(1000 * $event->getRelativeTime());
        $sbuf .= '</td>' . PHP_EOL;
        $sbuf .= '<td title="' . $event->getThreadName() . ' thread">';
        $sbuf .= $event->getThreadName();
        $sbuf .= '</td>' . PHP_EOL;
        $sbuf .= '<td title="Level">';

        $level = $event->getLevel();

        if ($level->equals(LoggerLevel::getLevelDebug())) {
            $sbuf .= '<font color="#339933">' . $level . '</font>';
        } elseif ($level->equals(LoggerLevel::getLevelWarn())) {
            $sbuf .= '<font color="#993300"><strong>' . $level . '</strong></font>';
        } else {
            $sbuf .= $level;
        }

        $sbuf .= '</td>' . PHP_EOL;
        $sbuf .= '<td title="' . htmlentities($event->getLoggerName(), ENT_QUOTES) . ' category">';
        $sbuf .= htmlentities($event->getLoggerName(), ENT_QUOTES);
        $sbuf .= '</td>' . PHP_EOL;

        if ($this->locationInfo) {
            $locInfo = $event->getLocationInformation();

            $sbuf .= '<td>';
            $sbuf .= htmlentities($locInfo->getFileName(), ENT_QUOTES) . ':' . $locInfo->getLineNumber();
            $sbuf .= '</td>' . PHP_EOL;
        }

        $sbuf .= '<td title="Message">';
        $sbuf .= htmlentities($event->getRenderedMessage(), ENT_QUOTES);
        $sbuf .= '</td>' . PHP_EOL;
        $sbuf .= '</tr>' . PHP_EOL;

        if ($event->getNDC() !== null) {
            $sbuf .= '<tr><td bgcolor="#EEEEEE" style="font-size : xx-small;" colspan="6"';
            $sbuf .= ' title="Nested Diagnostic Context">';
            $sbuf .= 'NDC: ' . htmlentities($event->getNDC(), ENT_QUOTES);
            $sbuf .= '</td></tr>' . PHP_EOL;
        }

        return $sbuf;
    }

    public function getHeader(): string
    {
        $date = new DateTime();

        $sbuf  = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"';
        $sbuf  = ' "http://www.w3.org/TR/html4/loose.dtd">' . PHP_EOL;
        $sbuf .= '<html>' . PHP_EOL;
        $sbuf .= '<head>' . PHP_EOL;
        $sbuf .= '<title>' . $this->title . '</title>' . PHP_EOL;
        $sbuf .= '<style type="text/css">' . PHP_EOL;
        $sbuf .= '<!--' . PHP_EOL;
        $sbuf .= 'body, table {font-family: arial,sans-serif; font-size: x-small;}' . PHP_EOL;
        $sbuf .= 'th {background: #336699; color: #FFFFFF; text-align: left;}' . PHP_EOL;
        $sbuf .= '-->' . PHP_EOL;
        $sbuf .= '</style>' . PHP_EOL;
        $sbuf .= '</head>' . PHP_EOL;
        $sbuf .= '<body bgcolor="#FFFFFF" topmargin="6" leftmargin="6">' . PHP_EOL;
        $sbuf .= '<hr size="1" noshade>' . PHP_EOL;
        $sbuf .= 'Log session start time ' . $date->format('Y-m-d H:i:s') . '<br>' . PHP_EOL;
        $sbuf .= '<br>' . PHP_EOL;
        $sbuf .= '<table cellspacing="0" cellpadding="4" border="1" bordercolor="#224466" width="100%">' . PHP_EOL;
        $sbuf .= '<tr>' . PHP_EOL;
        $sbuf .= '<th>Time</th>' . PHP_EOL;
        $sbuf .= '<th>Thread</th>' . PHP_EOL;
        $sbuf .= '<th>Level</th>' . PHP_EOL;
        $sbuf .= '<th>Category</th>' . PHP_EOL;

        if ($this->locationInfo) {
            $sbuf .= '<th>File:Line</th>' . PHP_EOL;
        }

        $sbuf .= '<th>Message</th>' . PHP_EOL;
        $sbuf .= '</tr>' . PHP_EOL;

        return $sbuf;
    }

    public function getFooter(): string
    {
        $sbuf  = '</table>' . PHP_EOL;
        $sbuf .= '<br>' . PHP_EOL;
        $sbuf .= '</body></html>';

        return $sbuf;
    }
}
