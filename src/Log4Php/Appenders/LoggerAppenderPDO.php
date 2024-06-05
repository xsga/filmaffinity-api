<?php

namespace Log4Php\Appenders;

use Log4Php\LoggerAppender;
use Log4Php\LoggerLoggingEvent;
use Log4Php\Layouts\LoggerLayoutPattern;
use Log4Php\Helpers\LoggerPatternParser;
use PDO;
use PDOException;
use PDOStatement;

class LoggerAppenderPDO extends LoggerAppender
{
    protected string $dsn = '';
    protected string $user = '';
    protected string $password = '';
    protected string $insertSQL = '';
    protected string $insertPattern = "%date{Y-m-d H:i:s},%logger,%level,%message,%pid,%file,%line";
    protected string $table = 'log4php_log';
    protected int $reconnectAttempts = 3;
    protected ?PDO $db = null;
    protected PDOStatement $preparedInsert;
    protected bool $requiresLayout = false;
    protected array $converters = [];

    public function __construct()
    {
        parent::__construct();

        $sql  = 'INSERT INTO __TABLE__ (timestamp, logger, level, message, thread, file, line) ';
        $sql .= 'VALUES (?, ?, ?, ?, ?, ?, ?)';

        $this->insertSQL = $sql;
    }

    public function activateOptions(): void
    {
        try {
            $this->establishConnection();
        } catch (PDOException $exception) {
            $this->warn('Failed connecting to database. Closing appender. Error: ' . $exception->getMessage());
            $this->close();
            return;
        }

        $pieces = explode(',', $this->insertPattern);
        $converterMap = LoggerLayoutPattern::getDefaultConverterMap();
        foreach ($pieces as $pattern) {
            $parser = new LoggerPatternParser($pattern, $converterMap);
            $this->converters[] = $parser->parse();
        }

        $this->closed = false;
    }

    protected function establishConnection(): void
    {
        $this->db = new PDO($this->dsn, $this->user, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $insertSQL = str_replace('__TABLE__', $this->table, $this->insertSQL);
        $this->preparedInsert = $this->db->prepare($insertSQL);
    }

    public function append(LoggerLoggingEvent $event): void
    {
        for ($attempt = 1; $attempt <= ($this->reconnectAttempts + 1); $attempt++) {
            try {
                $this->preparedInsert->execute($this->format($event));
                $this->preparedInsert->closeCursor();
                break;
            } catch (PDOException $exception) {
                $this->warn('Failed writing to database: ' . $exception->getMessage());

                if ($attempt > $this->reconnectAttempts) {
                    $log  = 'Failed writing to database after ' . $this->reconnectAttempts;
                    $log .= ' reconnect attempts. Closing appender';
                    $this->warn($log);
                    $this->close();
                } else {
                    $this->warn('Attempting a reconnect (attempt $attempt of ' . $this->reconnectAttempts . ')');
                    $this->establishConnection();
                }
            }
        }
    }

    protected function format(LoggerLoggingEvent $event): array
    {
        $params = [];

        foreach ($this->converters as $converter) {
            $buffer = '';
            while ($converter !== null) {
                $converter->format($buffer, $event);
                $converter = $converter->next;
            }
            $params[] = $buffer;
        }

        return $params;
    }

    public function close(): void
    {
        $this->db     = null;
        $this->closed = true;
    }

    public function getDatabaseHandle(): ?PDO
    {
        return $this->db;
    }

    public function setUser(string $user): void
    {
        $this->setString('user', $user);
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setPassword(string $password): void
    {
        $this->setString('password', $password);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setInsertSQL(string $sql): void
    {
        $this->setString('insertSQL', $sql);
    }

    public function getInsertSQL(): string
    {
        return $this->insertSQL;
    }

    public function setInsertPattern(string $pattern): void
    {
        $this->setString('insertPattern', $pattern);
    }

    public function getInsertPattern(): string
    {
        return $this->insertPattern;
    }

    public function setTable(string $table): void
    {
        $this->setString('table', $table);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setDSN(string $dsn): void
    {
        $this->setString('dsn', $dsn);
    }

    public function getDSN(): string
    {
        return $this->dsn;
    }
}
