<?php
/**
 * XsgaRestWrapper.
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
namespace xsgaphp\rest;

/**
 * Import dependencies.
 */
use xsgaphp\core\XsgaAbstractClass;

/**
 * XsgaRestWrapper class.
 */
class XsgaRestWrapper extends XsgaAbstractClass
{

    
    /**
     * Get page content.
     * 
     * @param string $url     URL.
     * @param array  $options CURL options.
     * 
     * @return string
     * 
     * @access public
     */
    public function getPageContent(string $url, array $options = array()) : string
    {
        // Logger.
        $this->logger->debugInit();
        
        // Initialize page content output.
        $pageContent = '';
        
        // Validates URL.
        if (empty($url)) {
            
            // Logger.
            $this->logger->error('Empty web URL');
            
        } else {
            
            // Get options.
            if (empty($options)) {
            
                $cURLOptions = $this->getUrlDefaultOptions();
            
            } else {
            
                $cURLOptions = $options;
            
            }//end if
            
            // Set petition.
            $petition = new XsgaRestClient($url);
            
            // Get response.
            $response = $petition->get($cURLOptions);
            
            $code = $response->getCode();

            // Logger.
            $this->logger->debug("HTTP response code: $code");
            
            if ($response->getCode() !== 200) {
                
                // Logger.
                $this->logger->error("HTTP ERROR: $code");
                $this->logger->error($response->getRaw());
                
            } else {
                
                // Get page content.
                $pageContent = $response->getBody();
                
            }//end if
            
        }//end if
        
        // Logger.
        $this->logger->debugEnd();
        
        return $pageContent;
        
    }//end getPageContent()
    
    
    /**
     * Get file.
     *
     * @param string $url     URL.
     * @param string $dest    Path and file name destination.
     * @param array  $options CURL options.
     *
     * @return boolean
     *
     * @access public
     */
    public function getFile(string $url, string $dest, array $options = array()) : bool
    {
        // Logger.
        $this->logger->debugInit();
        
        // Validates URL.
        if (empty($url)) {
    
            // Logger.
            $this->logger->error('Empty file URL');
            
            $out = false;
    
        } else {
    
            // Prepare file.
            $fp = fopen($dest, 'w');
            
            // Get options.
            if (empty($options)) {
    
                $cURLOptions = $this->getFileDefaultOptions($fp);
    
            } else {
    
                $cURLOptions = $options;
    
            }//end if
    
            // Set petition.
            $petition = new XsgaRestClient($url);
    
            // Get response.
            $response = $petition->get($cURLOptions);
            
            // Close file.
            fclose($fp);
    
            // Logger.
            $this->logger->debug("HTTP response code: $response->getCode()");
    
            if ($response->getCode() !== 200) {
    
                // Logger.
                $this->logger->error("HTTP ERROR: $response->getCode()");
                $this->logger->error($response->getErrors());
                
                $out = false;
    
            } else {
                
                $out = true;
                
            }//end if
    
        }//end if
    
        // Logger.
        $this->logger->debugEnd();
    
        return $out;
    
    }//end getFile()
    
    
    /**
     * Get default cURL options to get page content.
     * 
     * @return array
     * 
     * @access private
     */
    private function getUrlDefaultOptions() : array
    {
        // Logger.
        $this->logger->debugInit();
        
        // Set cURL options.
        $defaultOptions = array(
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true
        );
        
        // Logger.
        $this->logger->debugEnd();
        
        return $defaultOptions;
        
    }//end getUrlDefaultOptions()
    
    
    /**
     * Get default cURL options to get file.
     * 
     * @param resource $fp File open resource.
     * 
     * @return array
     * 
     * @access private
     */
    
    private function getFileDefaultOptions($fp) : array
    {
        // Logger.
        $this->logger->debugInit();
        
        // Set cURL options.
        $defaultOptions = array(
            CURLOPT_HEADER         => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FILE           => $fp
        );
        
        // Logger.
        $this->logger->debugEnd();
        
        return $defaultOptions;
        
    }//end getFileDefaultOptions()


}//end XsgaRestWrapper class
