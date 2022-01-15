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
            ->orderBy('m.follow','DESC')
            ->setMaxResults(5)
            ->getQuery();
            return $qb->getResult();
    }

    public function findByMostRecent()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.created_at','DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findByRankRange($min,$max)
    {
        $result_1 =  $this->createQueryBuilder('m')
            ->innerJoin('m.poolSet','p')
            ->innerJoin('p.tags','t')
            ->where('t.type = :rank_min AND t.name >= :min')
            ->setParameters(['min' =>$min, 'rank_min' => 'rank_min'])
            ->getQuery()->getResult();
        $ids = [];

        foreach ($result_1 as $id){
            array_push($ids,$id->getId());
        }

        $result_2 =  $this->createQueryBuilder('m')
            ->innerJoin('m.poolSet','p')
            ->innerJoin('p.tags','t')
            ->Where('t.type = :rank_max AND t.name <= :max')
            ->andWhere('m.id IN (:ids)')
            ->setParameters(['max' => $max, 'rank_max' => 'rank_max', 'ids' => $ids])
            ->orderBy('m.follow','DESC')
            ->getQuery()->getResult();

        return $result_2;

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
