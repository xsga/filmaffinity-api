<?php
/**
 * GenerateProxiesAction.
 * 
 * This file contains the GenerateProxiesAction class.
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
namespace xsgaphp\core\console\actions;

/**
 * Import dependencies.
 */
use xsgaphp\core\abstract\XsgaAbstractClass;
use xsgaphp\core\console\XsgaActionInt;
use xsgaphp\core\utils\XsgaPath;
use xsgaphp\core\exceptions\XsgaException;

/**
 * GenerateProxiesAction class.
 */
final class GenerateProxiesAction extends XsgaAbstractClass implements XsgaActionInt
{


    /**
     * Executes console action.
     * 
     * @param array $params Console action params.
     * 
     * @return void
     * 
     * @access public
     */
    public function execute(array $params) : void
    {
        // Logger.
        $this->logger->debugInit();
        $this->logger->info('Doctrine-ORM generate proxies');
        
        // Executes generates proxies.
        $this->execGenerate(XsgaPath::getPathTo(array('vendor', 'bin')));

        // Logger.
        $this->logger->debugEnd();

    }//end execute()


    /**
     * Executes generate proxies.
     * 
     * @param string $doctrinePath Doctrine console path.
     * 
     * @return void
     * 
     * @throws XsgaException
     * 
     * @access private
     */
    private function execGenerate(string $doctrinePath) : void
    {
        // Logger.
        $this->logger->debugInit();
        $this->logger->info('Generating entities proxies');
        
        // Generates proxies.
        exec('php "'.$doctrinePath.'doctrine" orm:generate-proxies', $output, $status);

        if ($status === 1) {

            // Error message.
            $errorMsg = 'Error creating entities proxies';

            // Logger.
            $this->logger->error($errorMsg);

            // Log output as error.
            $this->logOutput($output, 'error');

            throw new XsgaException($errorMsg);

        } else {

            // Log output.
            $this->logOutput($output, 'debug');

        }//end if

        // Logger.
        $this->logger->debugEnd();

    }//end execGenerate()


    /**
     * Log command output.
     * 
     * @param array  $output Output array.
     * @param string $type   Log type.
     * 
     * @return void
     * 
     * @access private
     */
    private function logOutput(array $output, string $type = '') : void
    {
        foreach ($output as $line) {

            $line = trim($line);

            if (!empty($line)) {

                switch ($type) {
                    case 'debug':
                        $this->logger->debug($line);
                        break;
                    case 'error':
                        $this->logger->error($line);
                        break;
                    case 'info':
                        $this->logger->info($line);
                        break;
                }//end switch

            }//end if

        }//end foreach

    }//end logOutput()


}//end GenerateProxiesAction class
