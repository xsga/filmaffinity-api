<?php
/**
 * LoggerAppenderPDO.
 * 
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * PHP Version 8
 * 
 * @package    Log4php
 * @subpackage Appenders
 * @link       http://logging.apache.org/log4php/docs/appenders/pdo.html Appender documentation
 */

/**
 * Namespace.
 */
namespace log4php\appenders;

/**
 * Import dependencies.
 */
use log4php\LoggerAppender;
use log4php\LoggerLoggingEvent;
use log4php\layouts\LoggerLayoutPattern;
use log4php\helpers\LoggerPatternParser;

/**
 * LoggerAppenderPDO appender logs to a database using the PHP's PDO extension.
 *
 * ## Configurable parameters: ##
 *
 * - dsn             - The Data Source Name (DSN) used to connect to the database.
 * - user            - Username used to connect to the database.
 * - password        - Password used to connect to the database.
 * - table           - Name of the table to which log entries are be inserted.
 * - insertSQL       - Sets the insert statement for a logging event. Defaults
 *                     to the correct one - change only if you are sure what you are doing.
 * - insertPattern   - The conversion pattern to use in conjuction with insert 
 *                     SQL. Must contain the same number of comma separated 
 *                     conversion patterns as there are question marks in the 
 *                     insertSQL.
 */
class LoggerAppenderPDO extends LoggerAppender
{

    /**
     * DSN string used to connect to the database.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $dsn;

    /**
     * Database user name.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $user;
    
    /**
     * Database password.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $password;
    
    /**
     * The insert query.
     * 
     * The __TABLE__ placeholder will be replaced by the table name from {@link $table}.
     *  
     * The questionmarks are part of the prepared statement, and they must 
     * match the number of conversion specifiers in {@link insertPattern}.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $insertSQL = '';

    /**
     * A comma separated list of {@link LoggerPatternLayout} format strings which replace the "?" in $insertSQL.
     * 
     * Must contain the same number of comma separated conversion patterns as there are question marks in $insertSQL.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $insertPattern = "%date{Y-m-d H:i:s},%logger,%level,%message,%pid,%file,%line";

    /**
     * Name of the table to which to append log events.
     * 
     * @var string
     * 
     * @access protected
     */
    protected $table = 'log4php_log';
    
    /**
     * The number of recconect attempts to make on failed append.
     * 
     * @var integer
     * 
     * @access protected
     */
    protected $reconnectAttempts = 3;
    
    /**
     * The PDO instance.
     * 
     * @var \PDO
     * 
     * @access protected
     */
    protected $db;
    
    /**
     * Prepared statement for the insert query.
     * 
     * @var \PDOStatement
     * 
     * @access protected
     */
    protected $preparedInsert;
    
    /**
     * This appender does not require a layout.
     * 
     * @var boolean
     * 
     * @access protected
     */
    protected $requiresLayout = false;
    
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Execute parent constructor.
        parent::__construct();
        
        // Create SQL insert.
        $sql  = 'INSERT INTO __TABLE__ (timestamp, logger, level, message, thread, file, line) ';
        $sql .= 'VALUES (?, ?, ?, ?, ?, ?, ?)';
        
        // Set SQL insert.
        $this->insertSQL = $sql;
        
    }//end __construct()
    
    
    /**
     * Acquires a database connection based on parameters.
     * 
     * Parses the insert pattern to create a chain of converters which will be
     * used in forming query parameters from logging events.
     * 
     * @return void
     * 
     * @throws \PDOException If connect or prepare fails.
     * 
     * @access public
     */
    public function activateOptions() : void
    {
        try {
            $this->establishConnection();
        } catch (\PDOException $e) {
            $this->warn("Failed connecting to database. Closing appender. Error: " . $e->getMessage());
            $this->close();
            return;
        }//end try

        // Parse the insert patterns; pattern parts are comma delimited.
        $pieces = explode(',', $this->insertPattern);
        $converterMap = LoggerLayoutPattern::getDefaultConverterMap();
        foreach ($pieces as $pattern) {
            $parser = new LoggerPatternParser($pattern, $converterMap);
            $this->converters[] = $parser->parse(); 
        }//end foreach
        
        $this->closed = false;
        
    }//end activateOptions()
    
    
    /**
     * Connects to the database, and prepares the insert query.
     * 
     * @return void
     * 
     * @access protected
     */
    protected function establishConnection() : void
    {
        // Acquire database connection.
        $this->db = new \PDO($this->dsn, $this->user, $this->password);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
        // Prepare the insert statement.
        $insertSQL = str_replace('__TABLE__', $this->table, $this->insertSQL);
        $this->preparedInsert = $this->db->prepare($insertSQL);
        
    }//end establishConnection()
    
    
    /**
     * Appends a new event to the database.
     * 
     * If writing to database fails, it will retry by re-establishing the 
     * connection up to $reconnectAttempts times. If writing still fails, 
     * the appender will close.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return void
     * 
     * @throws \PDOException If connect or prepare fails.
     * 
     * @access public
     */
    public function append(LoggerLoggingEvent $event) : void
    {
        for ($attempt = 1; $attempt <= ($this->reconnectAttempts + 1); $attempt++) {
            try {
                // Attempt to write to database.
                $this->preparedInsert->execute($this->format($event));
                $this->preparedInsert->closeCursor();
                break;
            } catch (\PDOException $e) {
                $this->warn("Failed writing to database: ". $e->getMessage());
                
                // Close the appender if it's the last attempt.
                if ($attempt > $this->reconnectAttempts) {
                    $log  = 'Failed writing to database after '.$this->reconnectAttempts;
                    $log .= ' reconnect attempts. Closing appender';
                    $this->warn($log);
                    $this->close();
                    // Otherwise reconnect and try to write again.
                } else {
                    $this->warn('Attempting a reconnect (attempt $attempt of '.$this->reconnectAttempts.')');
                    $this->establishConnection();
                }//end if
                
            }//end try
            
        }//end for
        
    }//end append()
    
    
    /**
     * Converts logging event to a series of database params using the converter chain which was set up on activation.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return array
     * 
     * @access protected
     */
    protected function format(LoggerLoggingEvent $event) : array
    {
        $params = array();
        
        foreach ($this->converters as $converter) {
            $buffer = '';
            while ($converter !== null) {
                $converter->format($buffer, $event);
                $converter = $converter->next;
            }//end while
            
            $params[] = $buffer;
        }//end foreach
        
        return $params;
        
    }//end format()
    
    
    /**
     * Closes the connection to the logging database.
     * 
     * @return void
     * 
     * @access public
     */
    public function close() : void
    {
        // Close the connection (if any).
        $this->db = null;
        
        // Close the appender.
        $this->closed = true;
        
    }//end close()
    
    
    /**
     * Returns the active database handle or null if not established.
     * 
     * @return \PDO
     * 
     * @access public
     */
    public function getDatabaseHandle() : \PDO
    {
        return $this->db;
        
    }//end getDatabaseHandle()
    
    
    /**
     * Sets the username.
     * 
     * @param string $user User.
     * 
     * @return void
     * 
     * @access public
     */
    public function setUser($user) : void
    {
        $this->setString('user', $user);
        
    }//end setUser()
    
    
    /**
     * Returns the username.
     * 
     * @param string $user User.
     *
     * @return string
     *
     * @access public
     */
    public function getUser($user) : string
    {
        return $this->user;
        
    }//end getUser()
    
    
    /**
     * Sets the password.
     *
     * @param string $password Password. 
     *
     * @return void
     *
     * @access public
     */
    public function setPassword($password) : void
    {
        $this->setString('password', $password);
        
    }//end setPassword()
    
    
    /**
     * Returns the password.
     * 
     * @param string $password Password.
     *
     * @return string
     *
     * @access public
     */
    public function getPassword($password) : string
    {
        return $this->password;
        
    }//end getPassword()
    
    
    /**
     * Sets the insert SQL.
     * 
     * @param string $sql Sql.
     *
     * @return void
     *
     * @access public
     */
    public function setInsertSQL($sql) : void
    {
        $this->setString('insertSQL', $sql);
        
    }//end setInsertSQL()
    
    
    /**
     * Returns the insert SQL.
     * 
     * @param string $sql Sql.
     *
     * @return string
     *
     * @access public
     */
    public function getInsertSQL($sql) : string
    {
        return $this->insertSQL;
        
    }//end getInsertSQL()

    
    /**
     * Sets the insert pattern.
     * 
     * @param string $pattern Pattern.
     *
     * @return void
     *
     * @access public
     */
    public function setInsertPattern($pattern) : void
    {
        $this->setString('insertPattern', $pattern);
        
    }//end setInsertPattern()
    
    
    /**
     * Returns the insert pattern.
     * 
     * @param string $pattern Pattern.
     *
     * @return string
     *
     * @access public
     */
    public function getInsertPattern($pattern) : string
    {
        return $this->insertPattern;
        
    }//end getInsertPattern()

    
    /**
     * Sets the table name.
     * 
     * @param string $table Table.
     *
     * @return void
     *
     * @access public
     */
    public function setTable($table) : void
    {
        $this->setString('table', $table);
        
    }//end setTable()
    
    
    /**
     * Returns the table name.
     * 
     * @param string $table Table.
     *
     * @return string
     *
     * @access public
     */
    public function getTable($table) : string
    {
        return $this->table;
        
    }//end getTable()
    
    
    /**
     * Sets the DSN string.
     * 
     * @param string $dsn Dns.
     *
     * @return void
     *
     * @access public
     */
    public function setDSN($dsn) : void
    {
        $this->setString('dsn', $dsn);
        
    }//end setDSN()
    
    
    /**
     * Returns the DSN string.
     * 
     * @param string $dsn Dns.
     *
     * @return string
     *
     * @access public
     */
    public function getDSN($dsn) : string
    {
        return $this->setString('dsn', $dsn);
        
    }//end getDSN()
    
    
}//end LoggerAppenderPDO class
