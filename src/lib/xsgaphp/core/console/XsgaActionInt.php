<?php
/**
 * XsgaActionInt.
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
 * Interface XsgaActionInt.
 */
interface XsgaActionInt 
{


    /**
     * Execute console action.
     * 
     * @param array $params Action parameters.
     * 
     * @return void
     * 
     * @access public
     */
    public function execute(array $params) : void;


}//end XsgaActionInt interface
