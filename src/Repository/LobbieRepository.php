<?php

namespace App\Repository;

use App\Entity\Lobbie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lobbie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lobbie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lobbie[]    findAll()
 * @method Lobbie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LobbieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lobbie::class);
    }

    // /**
    //  * @return Lobbie[] Returns an array of Lobbie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lobbie
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
