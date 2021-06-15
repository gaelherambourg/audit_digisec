<?php

namespace App\Repository;

use App\Entity\Societe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Societe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Societe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Societe[]    findAll()
 * @method Societe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Societe::class);
    }

    public function findAllInformationsBySociety($id)
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->join('s.adresse', 'a')->addSelect('a')
            ->join('s.contact', 'c')->addSelect('c');
        $queryBuilder
            ->andWhere('s.id = :id')->setParameter('id', $id);
        $query = $queryBuilder->getQuery();
        return $query->getSingleResult();
    }

    //Recherche de la saisie formulaire societe en BDD
    public function recherche($recherche_utilisateur)
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->leftJoin('s.adresse', 'a')->addSelect('a')
            ->join('s.contact', 'c')->addSelect('c');
        $queryBuilder
            ->andWhere('s.nom LIKE :ru')->setParameter(':ru', '%' . $recherche_utilisateur . '%')
            ->andWhere('s.est_digisec = 0');
        return $queryBuilder->getQuery()->getResult();
    }

    public function findAllInformationsDigisec(){
        //SELECT * FROM societe WHERE est_digisec = '1';
        $queryBuilder = $this->createQueryBuilder('s')
            ->leftJoin('s.adresse','a')->addSelect('a')
            ->join('s.contact','c')->addSelect('c');
        $queryBuilder
            ->andWhere('s.est_digisec = 1');
        $query = $queryBuilder->getQuery();
        return $query->getSingleResult();
    }

    public function findAllInformations()
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->leftJoin('s.adresse', 'a')->addSelect('a')
            ->join('s.contact', 'c')->addSelect('c');
        $queryBuilder
            ->andWhere('s.est_digisec = 0');
        $query = $queryBuilder->getQuery();
        return $query->getArrayResult();
    }

    // /**
    //  * @return Societe[] Returns an array of Societe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Societe
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
