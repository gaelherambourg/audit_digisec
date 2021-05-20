<?php

namespace App\Repository;

use App\Entity\TypePreuve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypePreuve|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePreuve|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePreuve[]    findAll()
 * @method TypePreuve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePreuveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypePreuve::class);
    }

    // /**
    //  * @return TypePreuve[] Returns an array of TypePreuve objects
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
    public function findOneBySomeField($value): ?TypePreuve
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
