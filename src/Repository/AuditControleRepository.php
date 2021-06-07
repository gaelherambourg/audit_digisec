<?php

namespace App\Repository;

use App\Entity\AuditControle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AuditControle|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuditControle|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuditControle[]    findAll()
 * @method AuditControle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuditControleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuditControle::class);
    }

    // /**
    //  * @return AuditControle[] Returns an array of AuditControle objects
    //  */
    
    public function findById($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.audit = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findAllPointControleByAuditAndRecommandation($id, $id_recommandation){
        $queryBuilder = $this->createQueryBuilder('c')
            ->join('c.pointControle','p')->addSelect('p')
            ->join('p.recommandation','r')->addSelect('r')
            ->join('p.remediation','e')->addSelect('e');
        $queryBuilder
            ->andWhere('c.audit = :id')->setParameter('id',$id)
            ->andWhere('r.id = :id_recommandation')->setParameter('id_recommandation', $id_recommandation);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?AuditControle
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
