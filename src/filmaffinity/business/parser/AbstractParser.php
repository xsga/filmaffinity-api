<?php
/**
 * AbstractParser.
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
namespace api\filmaffinity\business\parser;

/**
 * Import dependencies.
 */
use xsgaphp\core\abstract\XsgaAbstractClass;

/**
 * Class AbstractParser.
 */
abstract class AbstractParser extends XsgaAbstractClass
{

    /**
     * @var \DOMDocument
     * 
     * @access protected
     */
    protected $content;

    
    /**
     * Init DOMDocument.
     * 
     * @param string $pageContent Page content.
     * 
     * @return void
     * 
     * @access public
     */
    public function init(string $pageContent) : void
    {
        // Logger.
        $this->logger->debugInit();

        // New DOMDocument instance.
        $document = new \DOMDocument();

        // Disables parse output errors.
        libxml_use_internal_errors(true);
        
        // Loads film data page into DOMDocument.
        $document->loadHtml(mb_convert_encoding($pageContent, 'HTML-ENTITIES', 'UTF-8'));

        // Enable parse output errors.
        libxml_use_internal_errors(false);

        // Sets content.
        $this->content = $document;

        // Logger.
        $this->logger->debugEnd();

    }//end init()


    /**
     * Gets data form XPath query.
     * 
     * @param string  $query    XPath query.
     * @param boolean $outArray Output array.
     * 
     * @return array|\DOMNodeList
     * 
     * @access private
     */
    protected function getData(string $query, bool $outArray = true) : array|\DOMNodeList
    {
        // Logger.
        $this->logger->debugInit();

        // New DOMXPath instance.
        $domXpath = new \DOMXPath($this->content);

        // Get data.
        $data = $domXpath->query($query);

        if ($outArray) {

            // Initializes output.
            $out = array();

            for ($i = 0; $i < count($data); $i++) {
                $out[] = trim($data[$i]->nodeValue);
            }//end for

        } else {

            $out = $data;

        }//end if
        
        // Logger.
        $this->logger->debugEnd();

        return $out;

    }//end getData()
    
    
}//end AbstractParser class
