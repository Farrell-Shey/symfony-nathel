<?php

namespace App\Repository;

use App\Entity\GroupPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupPlayer[]    findAll()
 * @method GroupPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupPlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupPlayer::class);
    }

    // /**
    //  * @return GroupPlayer[] Returns an array of GroupPlayer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupPlayer
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
