<?php

namespace App\Repository;

use App\Entity\FormationFormateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FormationFormateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormationFormateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormationFormateur[]    findAll()
 * @method FormationFormateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationFormateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormationFormateur::class);
    }

    // /**
    //  * @return FormationFormateur[] Returns an array of FormationFormateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FormationFormateur
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
