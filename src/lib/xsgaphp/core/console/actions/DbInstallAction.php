<?php
/**
 * DbInstallAction.
 * 
 * This file contains the DbInstallAction class.
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
use xsgaphp\core\doctrine\XsgaDoctrineEM;

/**
 * DbInstallAction class.
 */
final class DbInstallAction extends XsgaAbstractClass implements XsgaActionInt
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
        $this->logger->info('Database install');
        $this->logger->info('Getting install files');

        // Get path to install scripts.
        $path = XsgaPath::getPathTo('scripts');
        
        // Get folder content.
        $files = scandir($path);

        // Unset '.' and '..' elements.
        unset($files[array_search('.', $files)]);
        unset($files[array_search('..', $files)]);

        // Logger.
        $this->logger->info(count($files).' files found');

        // Get entity manager.
        $em = XsgaDoctrineEM::getEntityManager();

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') {
                continue;
            }//end if

            // Logger.
            $this->logger->info("Executing file \"$file\"");

            // Gests file content.
            $fileContent = \file_get_contents($path.$file);

            // Load file content.
            $sql = $em->getConnection()->prepare($fileContent);

            // Execute file content.
            $sql->execute();

            // Logger.
            $this->logger->info("File \"$file\" executed successfully");

        }//end foreach

        // Logger.
        $this->logger->debugEnd();

    }//end execute()


}//end DbInstallAction class
