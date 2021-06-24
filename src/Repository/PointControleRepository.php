<?php

namespace App\Repository;

use App\Entity\PointControle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PointControle|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointControle|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointControle[]    findAll()
 * @method PointControle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointControleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PointControle::class);
    }

    public function findByExampleField($id)
    {
        return $this->createQueryBuilder('pc')
            ->join('pc.recommandation', 'r')->addSelect('r')
            ->join('r.chapitre','c')->addSelect('c')
            ->join('c.referentiel','ref')->addSelect('ref')
            ->andWhere('c.referentiel = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    public function pointControleParRecommandation($id)
    {
        return $this->createQueryBuilder('pc')
            ->join('pc.recommandation', 'r')->addSelect('r')
            ->andWhere('pc.recommandation = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return PointControle[] Returns an array of PointControle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PointControle
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
