<?php

namespace App\Repository;

use App\Entity\Referentiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Referentiel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referentiel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referentiel[]    findAll()
 * @method Referentiel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referentiel::class);
    }

    public function recherche($recherche_utilisateur)
    {
        //Recherche de la saisie formulaire societe en BDD
        $queryBuilder = $this->createQueryBuilder('r')
            ->andWhere('r.libelle LIKE :ru')
            ->setParameter(':ru', '%' . $recherche_utilisateur . '%');
        return $queryBuilder->getQuery()->getResult();
    }

    public function allInformation($id)
    {
        //Recherche de la saisie formulaire societe en BDD
        $queryBuilder = $this->createQueryBuilder('r')
            ->join('r.chapitres', 'c')->addSelect('c')
            ->join('c.recommandations', 'rec')->addSelect('rec')
            ->join('rec.points_controle', 'pc')->addSelect('pc')
            ->join('pc.remediations', 'rem')->addSelect('rem');
        $queryBuilder
            ->andWhere('r.id = :id')->setParameter('id', $id);
        $query = $queryBuilder->getQuery();
        return $query->getSingleResult();
    }

    public function allAuditInformation()
    {
        //Recherche de la saisie formulaire societe en BDD
        $queryBuilder = $this->createQueryBuilder('r')
            ->join('r.chapitres', 'c')->addSelect('c')
            ->join('c.recommandations', 'rec')->addSelect('rec');
        $query = $queryBuilder->getQuery();
        return $queryBuilder->getQuery()->getResult();
    }
    // /**
    //  * @return Referentiel[] Returns an array of Referentiel objects
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
    public function findOneBySomeField($value): ?Referentiel
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
