<?php
/**
 * XsgaConsole.
 * 
 * This file manage framework console.
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
namespace xsgaphp\core\console;

/**
 * Import dependencies.
 */
use xsgaphp\core\abstract\XsgaAbstractClass;
use xsgaphp\core\exceptions\XsgaValidationException;
use xsgaphp\core\console\XsgaActionInt;
use xsgaphp\core\utils\XsgaCheckFile;

/**
 * XsgaConsole class.
 */
class XsgaConsole extends XsgaAbstractClass
{

    /**
     * Action ID provided.
     * 
     * @var string
     * 
     * @access private
     */
    private $actionId = '';

    /**
     * Action information array.
     * 
     * @var array
     * 
     * @access private
     */
    private $action = array();

    /**
     * Arguments.
     * 
     * @var array
     * 
     * @access private
     */
    private $args = array();

    /**
     * Available actions.
     * 
     * @var array
     * 
     * @access private
     */
    private $actions = array();


    /**
     * Run console.
     * 
     * @param array $params Console parameters.
     * 
     * @return void
     * 
     * @access public
     */
    public function runConsole(array $params) : void
    {
        // Logger.
        $this->logger->debugInit();
        $this->logger->info('Starting console');

        try {

            // Get action ID and action arguments.
            $this->getActionAndArgs($params);

            // Get available actions.
            $this->getAvailableActions();

            // Get provided action information.
            $this->getAction();

            // Executes action.
            $this->executeAction();

        } catch (\Throwable $e) {

            // Logger.
            $this->logger->error($e->__toString());

        }//end try

        // Logger.
        $this->logger->info('Closing console');
        $this->logger->debugEnd();

    }//end runConsole()


    /**
     * Get console action and arguments.
     * 
     * @param array $params Console parameters.
     * 
     * @return void
     * 
     * @throws XsgaValidationException
     * 
     * @access private
     */
    private function getActionAndArgs(array $params) : void
    {
        // Logger.
        $this->logger->debugInit();

        // Validates if exists action.
        if (count($params) === 1) {
            
            // Error message.
            $errorMsg = 'No action provided to console';

            // Logger.
            $this->logger->error($errorMsg);

            throw new XsgaValidationException($errorMsg);

        }//end if

        // Quit script name from array.
        unset($params[0]);

        // Recalculation array indexes. 
        $params = array_values($params);

        // Set console action.
        $this->actionId = $params[0];

        // Quit action from array.
        unset($params[0]);

        // Set action arguments.
        foreach ($params as $key => $value) {
            
            $valueArray = explode('=', $value);

            if (count($valueArray) === 2) {

                // Logger.
                $this->logger->debug("Parameter \"$valueArray[0]\" added");

                // Add parameter.
                $this->args[$valueArray[0]] = $valueArray[1];

            } else {

                //Logger.
                $this->logger->error("Error adding parameter \"$value\"");

            }//end if
            
        }//end foreach

        // Logger.
        $this->logger->debugEnd();

    }//end getActionAndArgs()


    /**
     * Get available actions.
     * 
     * @return void
     * 
     * @throws XsgaValidationException Action files not found or not valid.
     * 
     * @access private
     */
    private function getAvailableActions() : void
    {
        // Logger.
        $this->logger->debugInit();

        // Load and check actions config file.
        if (XsgaCheckFile::consoleActions($output)) {

            // Logger.
            $this->logger->debug('Console actions configuration file loaded successfully');
            
            // Set actions.
            $this->actions = $output;
            
        } else {
            
            if (empty($output)) {
                // Error message.
                $errorMsg = 'Console actions configuration file not found';
            } else {
                // Error message.
                $errorMsg = 'Console actions configuration file not valid';
            }//end if
            
            // Logger.
            $this->logger->error($errorMsg);

            throw new XsgaValidationException($errorMsg);

        }//end if

        // Logger.
        $this->logger->debugEnd();

    }//end getAvailableActions()


    /**
     * Get action information array.
     * 
     * @return void
     * 
     * @throws XsgaValidationException No found action.
     * 
     * @access private
     */
    private function getAction() : void
    {
        // Logger.
        $this->logger->debugInit();

        $found = false;

        // search provided action in available action.
        foreach ($this->actions as $item) {

            if (strtolower($item['id']) === strtolower($this->actionId)) {
                
                // Logger.
                $this->logger->debug("Found action \"$item[name]\"");
                $this->logger->debugValidationOK();

                // Set action array information.
                $this->action = $item;
                
                // Set found to true.
                $found = true;

                break;

            }//end if

        }//end foreach

        // Error, no action found.
        if (!$found) {

            // Logger.
            $this->logger->debugValidationKO();
            $this->logger->error("No action found for \"$this->actionId\"");
                                    
            throw new XsgaValidationException('No action found');

        }//end if

        // Logger.
        $this->logger->debugEnd();

    }//end getAction()


    /**
     * Executes console action.
     * 
     * @return void
     * 
     * @throws XsgaObjectNotFoundException Action class not found.
     * @throws XsgaValidationException     Action class not valid.
     * 
     * @access private
     */
    private function executeAction() : void
    {
        // Logger.
        $this->logger->debugInit();

        // Set classname with namespace.
        $class = $this->action['class'];

        // Validates action class.
        if (!$this->existsAction($class)) {
            throw new XsgaObjectNotFoundException('Action class not found');
        } else if (!$this->validAction($class)) {
            throw new XsgaValidationException('Action class not valid');
        }//end if

        // Gets action instance.
        $action = new $class;

        // Executes action.
        $action->execute($this->args);

        // Logger.
        $this->logger->debugEnd();

    }//end executeAction()


    /**
     * Validates if exists action.
     * 
     * @param string $actionClass Action classname.
     * 
     * @return boolean
     * 
     * @access private
     */
    private function existsAction(string $actionClass) : bool
    {
        // Logger.
        $this->logger->debugInit();
        
        // Initialize output.
        $out = false;
                
        if (!class_exists($actionClass)) {

            // Logger.
            $this->logger->error("Action class \"$actionClass\" not found");
            $this->logger->debugValidationKO();
            
        } else {

            // Logger.
            $this->logger->debug('Action found');
            $this->logger->debugValidationOK();

            $out = true;

        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $out;
        
    }//end existsAction()


    /**
     * Validates if action class it's valid.
     * 
     * @param string $actionClass Action classname.
     * 
     * @return boolean
     * 
     * @access private
     */
    private function validAction(string $actionClass) : bool
    {
        // Logger.
        $this->logger->debugInit();
        
        // Initialize output.
        $out = false;
        
        if (!(in_array(XsgaActionInt::class, class_implements($actionClass)))) {

            // Logger.
            $this->logger->error("Action class \"$actionClass\" not valid");
            $this->logger->debugValidationKO();
            
        } else {

            // Logger.
            $this->logger->debug('Action class valid');
            $this->logger->debugValidationOK();

            $out = true;

        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $out;
        
    }//end validAction()


}//end XsgaConsole class
