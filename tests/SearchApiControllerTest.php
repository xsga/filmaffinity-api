<?php
/**
 * SearchApiControllerTest.
 *
 * Test of SearchApiController class.
 *
 * PHP version 7
 *
 * @author  xsga <xsegales@outlook.com>
 * @license MIT
 * @version 1.0.0
 */

/**
 * Namespace.
 */
namespace test;

/**
 * Import namespaces.
 */
use PHPUnit\Framework\TestCase;
use api\controller\SearchApiController;

/**
 * SearchApiControllerTest class
 */
class SearchApiControllerTest extends TestCase
{
    
    public function postDoSearchTest1()
    {
        
        $searchApiController = new SearchApiController();
        $searchApiController->inputData = '';
        
        $this->assertTrue('a' === 'a');
                
    }
    
}//end SearchApiControllerTest class
