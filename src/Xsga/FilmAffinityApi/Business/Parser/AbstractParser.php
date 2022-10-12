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
namespace Xsga\FilmAffinityApi\Business\Parser;

/**
 * Import dependencies.
 */
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractParser.
 */
abstract class AbstractParser
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access private
     */
    protected $logger;

    /**
     * DOMDocument object.
     *
     * @var DOMDocument
     *
     * @access protected
     */
    protected $content;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger      LoggerInterface instance.
     * @param DOMDocument     $domDocument DOMDocument instance.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, DOMDocument $domDocument)
    {
        $this->logger  = $logger;
        $this->content = $domDocument;
    }

    /**
     * Init DOMDocument.
     *
     * @param string $pageContent Page content.
     *
     * @return void
     *
     * @access public
     */
    public function init(string $pageContent): void
    {
        // Disables parse output errors.
        libxml_use_internal_errors(true);

        // Loads film data page into DOMDocument.
        $this->content->loadHtml(mb_convert_encoding($pageContent, 'HTML-ENTITIES', 'UTF-8'));

        // Enable parse output errors.
        libxml_use_internal_errors(false);
    }

    /**
     * Gets data form XPath query.
     *
     * @param string  $query    XPath query.
     * @param boolean $outArray Output array.
     *
     * @return array|DOMNodeList
     *
     * @access private
     */
    protected function getData(string $query, bool $outArray = true): array|DOMNodeList
    {
        // New DOMXPath instance.
        $domXpath = new DOMXPath($this->content);

        // Get data.
        $data = $domXpath->query($query);

        if ($outArray) {
            $out = array();

            for ($i = 0; $i < count($data); $i++) {
                $out[] = trim($data[$i]->nodeValue);
            }//end for
        } else {
            $out = $data;
        }//end if

        return $out;
    }
}
