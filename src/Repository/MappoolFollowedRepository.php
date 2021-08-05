<?php

namespace App\Repository;

use App\Entity\MappoolFollowed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MappoolFollowed|null find($id, $lockMode = null, $lockVersion = null)
 * @method MappoolFollowed|null findOneBy(array $criteria, array $orderBy = null)
 * @method MappoolFollowed[]    findAll()
 * @method MappoolFollowed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MappoolFollowedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MappoolFollowed::class);
    }

    // /**
    //  * @return MappoolFollowed[] Returns an array of MappoolFollowed objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MappoolFollowed
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
