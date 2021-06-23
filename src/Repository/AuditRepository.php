<?php

namespace App\Repository;

use App\Entity\Audit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Audit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Audit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Audit[]    findAll()
 * @method Audit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Audit::class);
    }
    //Récupère toutes les informations d'un audit
    public function findAuditAllInformation($id)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.referentiel', 'ref')->addSelect('ref')
            ->join('ref.chapitres', 'chap')->addSelect('chap')
            ->join('chap.recommandations', 'rec')->addSelect('rec')
            ->andWhere('a.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findAllAuditAllInformation()
    {
        return $this->createQueryBuilder('a')
            ->leftjoin('a.audits_controle', 'ac')->addSelect('ac')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAuditBySociete($id_societe){
        return $this->createQueryBuilder('a')
            ->andWhere('a.societe = :val')
            ->setParameter('val', $id_societe)
            ->orderBy('a.date_creation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    
    // /**
    //  * @return Audit[] Returns an array of Audit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    /*
    public function findOneBySomeField($value): ?Audit
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
