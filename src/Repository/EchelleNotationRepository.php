<?php

namespace App\Repository;

use App\Entity\EchelleNotation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EchelleNotation|null find($id, $lockMode = null, $lockVersion = null)
 * @method EchelleNotation|null findOneBy(array $criteria, array $orderBy = null)
 * @method EchelleNotation[]    findAll()
 * @method EchelleNotation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EchelleNotationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EchelleNotation::class);
    }

    // /**
    //  * @return EchelleNotation[] Returns an array of EchelleNotation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EchelleNotation
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
