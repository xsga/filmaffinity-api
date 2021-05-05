<?php
/**
 * Class XsgaSQLLogger.
 * 
 * PHP Version 8
 *
 * @author  xsga <parker@xsga.es>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace xsgaphp\doctrine;

/**
 * Import dependencies.
 */
use Doctrine\DBAL\Logging\SQLLogger;
use log4php\Logger;

/**
 * Class XsgaSQLLogger.
 */
class XsgaSQLLogger implements SQLLogger
{

    /**
     * Logger.
     * 
     * @var Logger
     * 
     * @access private
     */
    private static $logger;
    
    /**
     * Start time.
     * 
     * @var integer
     * 
     * @access private
     */
    private $start;
    
    
    /**
     * Logs a SQL statement somewhere.
     *
     * @param string     $sql    The SQL to be executed.
     * @param array|null $params The SQL parameters.
     * @param array|null $types  The SQL parameter types.
     *
     * @return void
     * 
     * @access public
     */
    public function startQuery($sql, array|null $params = null, array|null $types = null) : void
    {
        // Get logger.
        if (empty(static::$logger)) {
            
            // Set logger.
            static::$logger = Logger::getRootLogger();
            
        }//end if
        
        if ($_ENV['LOGGER_SQL'] === 'true') {

            // Logger.
            static::$logger->trace('***************************************************************************');
            static::$logger->trace('SQL QUERY:');
            static::$logger->trace($sql);
            
            if (!empty($params)) {
                
                // Logger.
                static::$logger->trace('SQL PARAMETERS:');
                
                foreach ($params as $key => $param) {
                    
                    $keyAux = $key + 1;

                    if ($param instanceof \DateTime) {
                        
                        $formattedDate = $param->format('Y-m-d H:i:s');

                        // Logger.
                        static::$logger->trace("P$keyAux --> $formattedDate");

                    } else {
                        
                        // Logger.
                        static::$logger->trace("P$keyAux --> $param");
                        
                    }//end if
                                        
                }//end foreach
                
            }//end if
            
            // Set start time.
            $this->start = microtime(true);
            
        }//end if
        
    }//end startQuery()
    
    
    /**
     * Marks the last started query as stopped. This can be used for timing of queries.
     *
     * @return void
     * 
     * @access public
     */
    public function stopQuery() : void
    {
        if ($_ENV['LOGGER_SQL'] === 'true') {
            
            // Logger.
            static::$logger->trace('EXECUTION TIME (in seconds):');
            static::$logger->trace(number_format(microtime(true) - $this->start, 4));
            static::$logger->trace('***************************************************************************');
            
        }//end if
        
    }//end stopQuery()
    
    
}//end XsgaSQLLogger class
