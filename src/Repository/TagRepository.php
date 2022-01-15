<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    // /**
    //  * @return Tag[] Returns an array of Tag objects
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
    public function findOneBySomeField($value): ?Tag
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findByTypeAndValue($type,$value)
    {
        $query = $this->createQueryBuilder('t')
            ->andWhere('t.type = :type')
            ->setParameter('type', $type)
            ->andWhere('t.name = :value')
            ->setParameter('value', $value);
        return $query->getQuery()->execute();
    }

    public function findByPoolset(int $id)
    {
        $entityManager = $this->getEntityManager();

        $query = $this->createQueryBuilder('t')
            ->join('t.poolSets', 'tps')
            ->addSelect('tps')
            ->where('tps.id = :id')
            //->andWhere('')
            ->setParameter('id', $id)

        ;

        return $query->getQuery()->execute();
    }
}
