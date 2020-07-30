<?php
/**
 * LoggerAppenderMongoDB.
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
 * PHP Version 5
 * 
 * @package    Log4php
 * @subpackage Appenders
 * @link       http://logging.apache.org/log4php/docs/appenders/mail-event.html Appender documentation
 */

namespace log4php\appenders;

use log4php\LoggerAppender;
use Monolog\Handler\Mongo;
use Exception;
use RuntimeException;
use InvalidArgumentException;
use log4php\LoggerLoggingEvent;

/**
 * Appender for writing to MongoDB.
 * 
 * This class was originally contributed by Vladimir Gorej.
 * 
 * ## Configurable parameters: ##
 * 
 * - **host** - Server on which mongodb instance is located. 
 * - **port** - Port on which the instance is bound.
 * - **databaseName** - Name of the database to which to log.
 * - **collectionName** - Name of the target collection within the given database.
 * - **username** - Username used to connect to the database.
 * - **password** - Password used to connect to the database.
 * - **timeout** - For how long the driver should try to connect to the database (in milliseconds).
 */
class LoggerAppenderMongoDB extends LoggerAppender
{
    
    /** 
     * Default prefix for the host.
     * 
     * @var string
     */    
    const DEFAULT_MONGO_URL_PREFIX = 'mongodb://';
    
    /**
     * Default value for host, without a prefix.
     *
     * @var string
     */
    const DEFAULT_MONGO_HOST = 'localhost';
    
    /**
     * Default value for port.
     * 
     * @var integer
     */
    const DEFAULT_MONGO_PORT = 27017;
    
    /**
     * Default value for databaseName.
     * 
     * @var string
     */
    const DEFAULT_DB_NAME = 'log4php_mongodb';
    
    /**
     * Default value for collectionName.
     * 
     * @var string
     */
    const DEFAULT_COLLECTION_NAME = 'logs';
    
    /**
     * Default value for timeout.
     * 
     * @var integer
     */
    const DEFAULT_TIMEOUT_VALUE = 3000;
    
    /**
     * Server on which mongodb instance is located.
     * 
     * @var string
     */
    protected $host;
    
    /**
     * Port on which the instance is bound.
     * 
     * @var integer
     */
    protected $port;
    
    /**
     * Name of the database to which to log.
     * 
     * @var string
     */
    protected $databaseName;
    
    /**
     * Name of the collection within the given database.
     * 
     * @var string
     */
    protected $collectionName;
            
    /**
     * Username used to connect to the database.
     * 
     * @var string
     */
    protected $userName;
    
    /**
     * Password used to connect to the database.
     * 
     * @var string
     */
    protected $password;
    
    /**
     * Timeout value used when connecting to the database (in milliseconds).
     * 
     * @var integer
     */
    protected $timeout;
    
    /**
     * Connection to the MongoDB instance.
     * 
     * @var Mongo
     */
    protected $connection;
    
    /**
     * The collection to which log is written.
     * 
     * @var MongoCollection
     */
    protected $collection;
    
    
    /**
     * Constructor.
     * 
     * @param string $name Name.
     * 
     * @access public
     */
    public function __construct($name='')
    {
        parent::__construct($name);
        
        $this->host = static::DEFAULT_MONGO_URL_PREFIX . static::DEFAULT_MONGO_HOST;
        $this->port = static::DEFAULT_MONGO_PORT;
        $this->databaseName = static::DEFAULT_DB_NAME;
        $this->collectionName = static::DEFAULT_COLLECTION_NAME;
        $this->timeout = static::DEFAULT_TIMEOUT_VALUE;
        $this->requiresLayout = false;
        
    }//end __constructor()
    
    
    /**
     * Setup db connection.
     * 
     * Based on defined options, this method connects to the database and creates a {@link $collection}.
     * 
     * @return void
     * 
     * @throws RuntimeException
     * 
     * @access public
     */
    public function activateOptions()
    {
        
        try {
            $array = array('timeout' => $this->timeout);
            $this->connection = new Mongo(sprintf('%s:%d', $this->host, $this->port), $array);
            $db = $this->connection->selectDB($this->databaseName);
            
            if ($this->userName !== null && $this->password !== null) {
                
                $authResult = $db->authenticate($this->userName, $this->password);
                
                if ($authResult['ok'] === floatval(0)) {
                    throw new RuntimeException($authResult['errmsg'], $authResult['ok']);
                }//end if
                
            }//end if
            
            $this->collection = $db->selectCollection($this->collectionName);
            
        } catch (MongoConnectionException $ex) {
            
            $this->closed = true;
            $this->warn(sprintf('Failed to connect to mongo deamon: %s', $ex->getMessage()));
            
        } catch (InvalidArgumentException $ex) {
            
            $this->closed = true;
            $this->warn(sprintf('Error while selecting mongo database: %s', $ex->getMessage()));
            
        } catch (RuntimeException $ex) {
            
            $this->closed = true;
            $this->warn('Invalid credentials for mongo database authentication');
            
        }//end try
        
    }//end activateOptions()
    

    /**
     * Appends a new event to the mongo database.
     *
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return void
     * 
     * @access public
     */
    public function append(LoggerLoggingEvent $event)
    {
        try {
            
            if ($this->collection !== null) {
                $this->collection->insert($this->format($event));
            }//end if
            
        } catch (MongoCursorException $ex) {
            
            $this->warn(sprintf('Error while writing to mongo collection: %s', $ex->getMessage()));
            
        }//end try
        
    }//end append()
    
    
    /**
     * Converts the logging event into an array which can be logged to mongodb.
     * 
     * @param LoggerLoggingEvent $event Event.
     * 
     * @return array The array representation of the logging event.
     * 
     * @access protected
     */
    protected function format(LoggerLoggingEvent $event)
    {

        $timestampSec  = (int)$event->getTimestamp();
        $timestampUsec = (int)(($event->getTimestamp() - $timestampSec) * 1000000);

        $document = array(
                     'timestamp'  => new MongoDate($timestampSec, $timestampUsec),
                     'level'      => $event->getLevel()->toString(),
                     'thread'     => (int)$event->getThreadName(),
                     'message'    => $event->getMessage(),
                     'loggerName' => $event->getLoggerName()
                    );    

        $locationInfo = $event->getLocationInformation();
        
        if ($locationInfo !== null) {
            
            if ($locationInfo->getLineNumber() === 'NA') {
                $lineNumber = 'NA';
            } else {
                $lineNumber = (int)$locationInfo->getLineNumber();
            }//end if
            
            $document['fileName']   = $locationInfo->getFileName();
            $document['method']     = $locationInfo->getMethodName();
            $document['lineNumber'] = $lineNumber;
            $document['className']  = $locationInfo->getClassName();
            
        }//end if

        $throwableInfo = $event->getThrowableInformation();
        
        if ($throwableInfo !== null) {
            $document['exception'] = $this->formatThrowable($throwableInfo->getThrowable());
        }//end if
        
        return $document;
        
    }//end format()
    
    
    /**
     * Converts an Exception into an array which can be logged to mongodb.
     * 
     * Supports innner exceptions (PHP >= 5.3).
     * 
     * @param Exception $ex Exception.
     * 
     * @return array
     * 
     * @access protected
     */
    protected function formatThrowable(Exception $ex)
    {
        $array = array(                
                  'message'    => $ex->getMessage(),
                  'code'       => $ex->getCode(),
                  'stackTrace' => $ex->getTraceAsString(),
                 );
        
        if ((method_exists($ex, 'getPrevious') === true) && ($ex->getPrevious() !== null)) {
            $array['innerException'] = $this->formatThrowable($ex->getPrevious());
        }//end if
        
        return $array;
        
    }//end formatThrowable()
    
        
    /**
     * Closes the connection to the logging database.
     * 
     * @return void
     * 
     * @access public
     */
    public function close()
    {

        if ($this->closed !== true) {
            
            $this->collection = null;
            
            if ($this->connection !== null) {

                $this->connection->close();
                $this->connection = null;
                
            }//end if
            
            $this->closed = true;
            
        }//end if
        
    }//end close()
    
    
    /**
     * Sets the value of {@link $host} parameter.
     * 
     * @param string $host Host.
     * 
     * @return void
     * 
     * @access public
     */
    public function setHost($host)
    {
        if (preg_match('/^mongodb\:\/\//', $host) === false) {
            $host = static::DEFAULT_MONGO_URL_PREFIX . $host;
        }//end if
        
        $this->host = $host;
        
    }//end setHost()
    
        
    /**
     * Returns the value of {@link $host} parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getHost()
    {
        return $this->host;
        
    }//end getHost()
    

    /**
     * Sets the value of {@link $port} parameter.
     * 
     * @param integer $port Port.
     * 
     * @return void
     * 
     * @access pubic
     */
    public function setPort($port)
    {
        $this->setPositiveInteger('port', $port);
        
    }//end setPort()
    
        
    /**
     * Returns the value of {@link $port} parameter.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getPort()
    {
        return $this->port;
        
    }//end getPort()
    

    /**
     * Sets the value of {@link $databaseName} parameter.
     * 
     * @param string $databaseName Database name.
     * 
     * @return void
     * 
     * @access public
     */
    public function setDatabaseName($databaseName)
    {
        $this->setString('databaseName', $databaseName);
        
    }//end setDatabaseName()
    
        
    /**
     * Returns the value of {@link $databaseName} parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
        
    }//end getDatabaseName()
    

    /**
     * Sets the value of {@link $collectionName} parameter.
     * 
     * @param string $collectionName Collection name.
     * 
     * @return void
     * 
     * @access public
     */
    public function setCollectionName($collectionName)
    {
        $this->setString('collectionName', $collectionName);
        
    }//end setCollectionName()
    
        
    /**
     * Returns the value of {@link $collectionName} parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getCollectionName()
    {
        return $this->collectionName;
        
    }//end getCollectionName()
    

    /**
     * Sets the value of {@link $userName} parameter.
     * 
     * @param string $userName User name.
     * 
     * @return void
     * 
     * @access public
     */
    public function setUserName($userName)
    {
        $this->setString('userName', $userName, true);
        
    }//end setUserName()
    
    
    /**
     * Returns the value of {@link $userName} parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getUserName()
    {
        return $this->userName;
        
    }//end getUserName()
    

    /**
     * Sets the value of {@link $password} parameter.
     * 
     * @param string $password Password.
     * 
     * @return void
     * 
     * @access public
     */
    public function setPassword($password)
    {
        $this->setString('password', $password, true);
        
    }//end setPassword()
    
        
    /**
     * Returns the value of {@link $password} parameter.
     * 
     * @return string
     * 
     * @access public
     */
    public function getPassword()
    {
        return $this->password;
        
    }//end getPassword()
    

    /**
     * Sets the value of {@link $timeout} parameter.
     * 
     * @param integer $timeout Timeout.
     * 
     * @return void
     * 
     * @access public
     */
    public function setTimeout($timeout)
    {
        $this->setPositiveInteger('timeout', $timeout);
        
    }//end setTimeout()
    

    /**
     * Returns the value of {@link $timeout} parameter.
     * 
     * @return integer
     * 
     * @access public
     */
    public function getTimeout()
    {
        return $this->timeout;
        
    }//end getTimeout()
    
    
    /**
     * Returns the mongodb connection.
     * 
     * @return Mongo
     * 
     * @access public
     */
    public function getConnection()
    {
        return $this->connection;
        
    }//end getConnection()
    
    
    /**
     * Returns the active mongodb collection.
     * 
     * @return MongoCollection
     * 
     * @access public
     */
    public function getCollection()
    {
        return $this->collection;
        
    }//end getCollection()
    
    
}//end LoggerAppenderMongoDB class
