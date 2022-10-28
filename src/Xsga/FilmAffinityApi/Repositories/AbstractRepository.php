<?php

/**
 * AbstractRepository.
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
namespace Xsga\FilmAffinityApi\Repositories;

/**
 * Import dependencies.
 */
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * AbstractRepository class.
 */
abstract class AbstractRepository
{
    /**
     * Logger.
     *
     * @var LoggerInterface
     *
     * @access protected
     */
    protected $logger;

    /**
     * Doctrine entity manager.
     *
     * @var EntityManagerInterface
     *
     * @access protected
     */
    protected $em;

    /**
     * Entity repository.
     *
     * @var ObjectRepository
     *
     * @access protected
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param LoggerInterface        $logger LoggerInterface instance.
     * @param EntityManagerInterface $em     EntityManagerInterface instance.
     * @param string                 $entity Entity classname.
     *
     * @access public
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, string $entity)
    {
        $this->logger     = $logger;
        $this->em         = $em;
        $this->repository = $this->em->getRepository($entity);
    }
}
