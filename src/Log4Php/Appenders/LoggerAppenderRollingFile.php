<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerException;

class LoggerAppenderRollingFile extends LoggerAppenderFile
{
    public const int COMPRESS_CHUNK_SIZE = 102400;

    protected int $maxFileSize = 10485760;
    protected int $maxBackupIndex = 1;
    protected bool $compress = false;

    public function __construct(string $name = '')
    {
        parent::__construct($name);
    }

    public function getMaximumFileSize(): int
    {
        return $this->maxFileSize;
    }

    private function rollOver(): void
    {
        if ($this->maxBackupIndex > 0) {
            $file = $this->file . '.' . $this->maxBackupIndex;

            if (file_exists($file) && !unlink($file)) {
                throw new LoggerException("Unable to delete oldest backup file from [$file].");
            }

            $this->renameArchievedLogs($this->file);
            $this->moveToBackup($this->file);
        }

        ftruncate($this->fp, 0);
        rewind($this->fp);
    }

    private function moveToBackup(string $source): void
    {
        if ($this->compress) {
            $target = $source . '.1.gz';
            $this->compressFile($source, $target);
            return;
        }

        $target = $source . '.1';
        copy($source, $target);
    }

    private function compressFile(string $source, string $target): void
    {
        $target = "compress.zlib://$target";

        $fin = fopen($source, 'rb');
        if (!$fin) {
            throw new LoggerException("Unable to open file for reading: [$source].");
        }

        $fout = fopen($target, 'wb');
        if (!$fout) {
            throw new LoggerException("Unable to open file for writing: [$target].");
        }

        while (!feof($fin)) {
            $chunk = fread($fin, static::COMPRESS_CHUNK_SIZE);
            if (fwrite($fout, $chunk) === false) {
                throw new LoggerException('Failed writing to compressed file.');
            }
        }

        fclose($fin);
        fclose($fout);
    }

    private function renameArchievedLogs(string $fileName): void
    {
        for ($i = ($this->maxBackupIndex - 1); $i >= 1; $i--) {
            $source = $fileName . '.' . $i;
            if ($this->compress) {
                $source .= '.gz';
            }

            if (file_exists($source)) {
                $target = $fileName . '.' . ($i + 1);
                if ($this->compress) {
                    $target .= '.gz';
                }
                rename($source, $target);
            }
        }
    }

    protected function write(?string $string): void
    {
        if (!$this->fp && !$this->openFile()) {
            return;
        }

        if (flock($this->fp, LOCK_EX)) {
            if (fwrite($this->fp, is_null($string) ? '' : $string) === false) {
                $this->warn('Failed writing to file. Closing appender.');
                $this->closed = true;
            }

            clearstatcache(true, $this->file);

            if (filesize(realpath($this->file)) > $this->maxFileSize) {
                try {
                    $this->rollOver();
                } catch (LoggerException $ex) {
                    $this->warn('Rollover failed: ' . $ex->getMessage() . '. Closing appender.');
                    $this->closed = true;
                }
            }

            flock($this->fp, LOCK_UN);

            return;
        }

        $this->warn('Failed locking file for writing. Closing appender.');
        $this->closed = true;
    }

    public function activateOptions(): void
    {
        parent::activateOptions();

        if ($this->compress && !extension_loaded('zlib')) {
            $this->warn("The 'zlib' extension is required for file compression. Disabling compression.");
            $this->compress = false;
        }
    }

    public function setMaxBackupIndex(int $maxBackupIndex): void
    {
        $this->setPositiveInteger('maxBackupIndex', $maxBackupIndex);
    }

    public function getMaxBackupIndex(): int
    {
        return $this->maxBackupIndex;
    }

    public function setMaxFileSize(mixed $maxFileSize): void
    {
        $this->setFileSize('maxFileSize', $maxFileSize);
    }

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    public function setCompress(bool $compress): void
    {
        $this->setBoolean('compress', $compress);
    }

    public function getCompress(): bool
    {
        return $this->compress;
    }
}
