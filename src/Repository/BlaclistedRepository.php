<?php

namespace App\Repository;

use App\Entity\Blaclisted;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blaclisted|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blaclisted|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blaclisted[]    findAll()
 * @method Blaclisted[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlaclistedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blaclisted::class);
    }

    // /**
    //  * @return Blaclisted[] Returns an array of Blaclisted objects
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
    public function findOneBySomeField($value): ?Blaclisted
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
