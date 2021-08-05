<?php

namespace App\Repository;

use App\Entity\TourneyStaff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TourneyStaff|null find($id, $lockMode = null, $lockVersion = null)
 * @method TourneyStaff|null findOneBy(array $criteria, array $orderBy = null)
 * @method TourneyStaff[]    findAll()
 * @method TourneyStaff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TourneyStaffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TourneyStaff::class);
    }

    // /**
    //  * @return TourneyStaff[] Returns an array of TourneyStaff objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TourneyStaff
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
