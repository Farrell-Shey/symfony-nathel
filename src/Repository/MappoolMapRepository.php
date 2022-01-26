<?php

namespace App\Repository;

use App\Entity\MappoolMap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MappoolMap|null find($id, $lockMode = null, $lockVersion = null)
 * @method MappoolMap|null findOneBy(array $criteria, array $orderBy = null)
 * @method MappoolMap[]    findAll()
 * @method MappoolMap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MappoolMapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MappoolMap::class);
    }

    public function findByMapAndMappool($map, $mappool)
    {

        return $this->createQueryBuilder('m')
            ->andWhere('m.beatmap = :map')
            ->andWhere('m.mappool = :mappool')
            ->setParameters(['map'=> $map,'mappool' => $mappool])
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }



    // /**
    //  * @return MappoolMap[] Returns an array of MappoolMap objects
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
    public function findOneBySomeField($value): ?MappoolMap
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
