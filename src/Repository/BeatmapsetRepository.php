<?php

namespace App\Repository;

use App\Entity\Beatmapset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Beatmapset|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beatmapset|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beatmapset[]    findAll()
 * @method Beatmapset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeatmapsetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beatmapset::class);
    }

    // /**
    //  * @return Beatmapset[] Returns an array of Beatmapset objects
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
    public function findOneBySomeField($value): ?Beatmapset
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
