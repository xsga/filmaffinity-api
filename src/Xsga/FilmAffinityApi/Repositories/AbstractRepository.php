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
     * Entity repository.
     */
    protected ObjectRepository $repository;

    /**
     * Constructor.
     */
    public function __construct(
        protected LoggerInterface $logger,
        protected EntityManagerInterface $em,
        string $entity
    ) {
        $this->repository = $this->em->getRepository($entity);
    }
}
