<?php

namespace App\Repository;

use App\Entity\Recommandation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Recommandation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recommandation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recommandation[]    findAll()
 * @method Recommandation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecommandationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recommandation::class);
    }
    //Compte le nombre de recommandation par référentiel
    public function nbRecommandationByReferentieo($id_referentiel){
        $queryBuilder = $this->createQueryBuilder('r');
        return $queryBuilder
            ->join('r.chapitre','c')->addSelect('c')
            ->andWhere('c.referentiel = :id')
            ->setParameter('id',$id_referentiel)
            ->select('COUNT(r.id) AS cnt')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByExampleField($id)
    {
        return $this->createQueryBuilder('r')
            ->join('r.chapitre','c')->addSelect('c')
            ->join('c.referentiel','ref')->addSelect('ref')
            ->andWhere('c.referentiel = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Recommandation[] Returns an array of Recommandation objects
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
    public function findOneBySomeField($value): ?Recommandation
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
