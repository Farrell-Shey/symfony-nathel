<?php

namespace App\Repository;

use App\Entity\Mappool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mappool|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mappool|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mappool[]    findAll()
 * @method Mappool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MappoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mappool::class);
    }


    public function findById()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.id = :id')
            ->getQuery()
            ->getResult();
    }

    public function findByPopularity()
    {
       $qb = $this->createQueryBuilder('m')
            ->orderBy('m.id','DESC')
            ->setMaxResults(5)
            ->getQuery();
            return $qb->getResult();
    }

    public function findByMostRecent()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.created_at','ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Mappool
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
