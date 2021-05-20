<?php

namespace App\Repository;

use App\Entity\Remediation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Remediation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Remediation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Remediation[]    findAll()
 * @method Remediation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RemediationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Remediation::class);
    }

    // /**
    //  * @return Remediation[] Returns an array of Remediation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Remediation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
