<?php
/**
 * XsgaAbstractApiDao.
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
namespace xsgaphp\api\repository;

/**
 * Import dependencies.
 */
use xsgaphp\core\abstract\XsgaAbstractDao;
use xsgaphp\api\dto\ApiFiltersDto;
use Doctrine\ORM\QueryBuilder;

/**
 * XsgaAbstractApiDao class.
 */
abstract class XsgaAbstractApiDao extends XsgaAbstractDao
{
    
    /**
     * Get query filtered.
     * 
     * @param QueryBuilder  $queryBuilder Query builder instance.
     * @param string        $alias        Alias.
     * @param ApiFiltersDto $filters      Query filters.
     * 
     * @return QueryBuilder
     * 
     * @access public
     */
    public function getQueryFiltered(QueryBuilder $queryBuilder, string $alias, ApiFiltersDto $filters) : QueryBuilder
    {
        // Logger.
        $this->logger->debugInit();

        if (!empty($filters->getSortFields())) {

            foreach ($filters->getSortFields() as $field) {
                $queryBuilder->addOrderBy($alias.'.'.$field['field'], $field['sort']);
            }//end foreach

        }//end if

        if (!empty($filters->getPageNumber()) && !empty($filters->getPageSize())) {

            $offset = (($filters->getPageNumber() - 1) * $filters->getPageSize()) + 1;
            $total  = ($offset + $filters->getPageSize()) - 1;

            $queryBuilder->setFirstResult($offset);
            $queryBuilder->setMaxResults($total);

        }//end if

        // Logger.
        $this->logger->debugEnd();

        return $queryBuilder;

    }//end getQueryFiltered()
    
    
}//end XsgaAbstractApiDao class
