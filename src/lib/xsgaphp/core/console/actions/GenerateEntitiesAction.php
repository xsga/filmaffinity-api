<?php
/**
 * GenerateEntitiesAction.
 * 
 * This file contains the GenerateEntitiesAction class.
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
use xsgaphp\core\exceptions\XsgaValidationException;
use xsgaphp\core\exceptions\XsgaException;

/**
 * GenerateEntitiesAction class.
 */
final class GenerateEntitiesAction extends XsgaAbstractClass implements XsgaActionInt
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
        $this->logger->info('Doctrine-ORM generate entities');

        // Validates parameters.
        $this->validateParams($params);

        // Init variables.
        $doctrinePath    = XsgaPath::getPathTo(array('vendor', 'bin'));
        $tmpFolder       = substr(XsgaPath::getPathTo(array('tmp', 'entity')), 0, -1);
        $namespace       = $params['type'].'\\common\\persistence\\entity\\';
        $tmpEntityFolder = XsgaPath::getPathTo(array('tmp', 'entity', $params['type'], 'common', 'persistence', 'entity'));
        $entityFolder    = XsgaPath::getPathTo(array('src', 'common', 'persitence', 'entity'));

        // Executes generates entities.
        $this->execGenerate($doctrinePath, $namespace, $tmpFolder);

        // Copy entities to entity folder.
        $this->copyEntities($tmpEntityFolder, $entityFolder);

        // Executes add information to entites.
        $this->execAddInfo($doctrinePath, $tmpFolder);
        
        // Copy entities to entity folder.
        $this->copyEntities($tmpEntityFolder, $entityFolder);

        // Remove temporal folders.
        $this->rmDirs($tmpFolder, $params['type']);
        
        // Logger.
        $this->logger->debugEnd();

    }//end execute()


    /**
     * Validates parameters.
     * 
     * @param array $params Parameters array.
     * 
     * @return void
     * 
     * @throws XsgaValidationException Parameter TYPE not exists or not valid.
     * 
     * @access private
     */
    private function validateParams(array $params) : void
    {
        // Logger.
        $this->logger->debugInit();
        $this->logger->info('Validating type parameter');

        if (!isset($params['type'])) {
            
            // Error message.
            $errorMsg = 'Parameter type not exists';

            // Logger.
            $this->logger->error($errorMsg);
            $this->logger->debugValidationKO();

            throw new XsgaValidationException($errorMsg);

        }//end if

        if ($params['type'] !== 'api' && $params['type'] !== 'batch') {
            
            // Error message.
            $errorMsg = 'Parameter type "'.$params['type'].'" not valid (allowed values: "api" or "batch")';

            // Logger.
            $this->logger->error($errorMsg);
            $this->logger->debugValidationKO();

            throw new XsgaValidationException($errorMsg);

        }//end if

        // Logger.
        $this->logger->debugValidationOK();
        $this->logger->debugEnd();

    }//end validateParams()


    /**
     * Executes generate entities.
     * 
     * @param string $doctrinePath Doctrine console path.
     * @param string $namespace    Entities namespace.
     * @param string $tmpFolder    Temporal folder.
     * 
     * @return void
     * 
     * @throws XsgaException
     * 
     * @access private
     */
    private function execGenerate(string $doctrinePath, string $namespace, string $tmpFolder) : void
    {
        // Logger.
        $this->logger->debugInit();
        $this->logger->info('Mapping entities');
        
        // Generates entities.
        exec('php "'.$doctrinePath.'doctrine" orm:convert:mapping --force --from-database --namespace='.$namespace.' annotation "'.$tmpFolder.'"', $output, $status);

        if ($status === 1) {

            // Error message.
            $errorMsg = 'Error mapping entities';

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
     * Executes add information to entities.
     * 
     * @param string $doctrinePath Doctrine console path.
     * @param string $tmpFolder    Temporal folder.
     * 
     * @return void
     * 
     * @throws XsgaException
     * 
     * @access private
     */
    private function execAddInfo(string $doctrinePath, string $tmpFolder) : void
    {
        // Logger.
        $this->logger->debugInit();
        $this->logger->info('Adding information to entities');
        
        // Add information to entities.
        exec('php "'.$doctrinePath.'doctrine" orm:generate:entities "'.$tmpFolder.'" --generate-annotations=true', $output, $status);
        
        if ($status === 1) {

            // Error message.
            $errorMsg = 'Error adding information to entities';

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

    }//end execAddInfo()


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


    /**
     * Copy entities.
     * 
     * @param string $from Origin folder.
     * @param string $to   Destination folder.
     * 
     * @return void
     * 
     * @access private
     */
    private function copyEntities(string $from, string $to) : void
    {
        // Logger.
        $this->logger->debugInit();

        // Get folder content.
        $files = \scandir($from);

        foreach ($files as $file) {

            if ($file === '.' || $file === '..') {
                continue;
            }//end if

            // Copy entity file to destination folder.
            \copy($from.$file, $to.$file);

            // Delete entity file from origin folder.
            \unlink($from.$file);

        }//end foreach

        // Logger.
        $this->logger->debugEnd();

    }//end copyEntities()


    /**
     * Remove working directories.
     * 
     * @param string $tmpFolder Temporal folder.
     * @param string $type      Type
     * 
     * @return void
     * 
     * @access private
     */
    private function rmDirs(string $tmpFolder, string $type) : void
    {
        // Logger.
        $this->logger->debugInit();
        $this->logger->info('Removing temporal folders');

        \rmdir($tmpFolder.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'persistence'.DIRECTORY_SEPARATOR.'entity');
        \rmdir($tmpFolder.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'persistence');
        \rmdir($tmpFolder.DIRECTORY_SEPARATOR.$type.DIRECTORY_SEPARATOR.'common');
        \rmdir($tmpFolder.DIRECTORY_SEPARATOR.$type);
        \rmdir($tmpFolder);        

        // Logger.
        $this->logger->debugEnd();

    }//end rmDirs()


}//end GenerateEntitiesAction class
