<?php

namespace App\Repository;

use App\Entity\Beatmap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Beatmap|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beatmap|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beatmap[]    findAll()
 * @method Beatmap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeatmapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beatmap::class);
    }

    // /**
    //  * @return Beatmap[] Returns an array of Beatmap objects
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
    public function findOneBySomeField($value): ?Beatmap
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
