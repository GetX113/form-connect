<?php

namespace App\Repository;

use App\Entity\DomainFormation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DomainFormation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainFormation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomainFormation[]    findAll()
 * @method DomainFormation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomainFormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomainFormation::class);
    }

    // /**
    //  * @return DomainFormation[] Returns an array of DomainFormation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DomainFormation
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
