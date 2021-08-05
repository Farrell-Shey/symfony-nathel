<?php

namespace App\Repository;

use App\Entity\Blacklisted;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blacklisted|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blacklisted|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blacklisted[]    findAll()
 * @method Blacklisted[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlacklistedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blacklisted::class);
    }

    // /**
    //  * @return Blacklisted[] Returns an array of Blacklisted objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blacklisted
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
