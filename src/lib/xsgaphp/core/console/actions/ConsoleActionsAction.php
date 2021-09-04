<?php
/**
 * ConsoleActionsAction.
 * 
 * This file contains the ConsoleActionsAction class.
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
use xsgaphp\core\utils\XsgaLoadFile;

/**
 * ConsoleActionsAction class.
 */
final class ConsoleActionsAction extends XsgaAbstractClass implements XsgaActionInt
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
        $this->logger->info('Available console actions');

        // Get actions.
        $actions = $this->getActions();

        // Print information.
        $this->printInfo($actions);

        // Logger.
        $this->logger->debugEnd();

    }//end execute()


    /**
     * Get available actions.
     * 
     * @return void
     * 
     * @access private
     */
    private function getActions() : array
    {
        // Logger.
        $this->logger->debugInit();

        // Get actions.
        $actions = XsgaLoadFile::loadJson(XsgaPath::getPathTo('config'), 'console-actions.json');

        // Logger.
        $this->logger->debugEnd();

        return $actions;

    }//end getActions()


    /**
     * Prints console actions information.
     * 
     * @param array $data Console actions information.
     * 
     * @return void
     * 
     * @access private
     */
    private function printInfo(array $data) : void
    {
        // Logger.
        $this->logger->debugInit();

        $output = "\n";

        foreach ($data as $action) {

            $id   = $action['id'];
            $name = $action['name'];
            $desc = $action['desc'];

            $output .= "   Action      : $id\n";
            $output .= "   Name        : $name\n";
            $output .= "   Description : $desc\n";
            $output .= "\n";

        }//end foreach

        echo $output;

        // Logger.
        $this->logger->debugEnd();

    }//end printInfo()


}//end ConsoleActionsAction class
