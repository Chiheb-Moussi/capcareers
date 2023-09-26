<?php

namespace App\Repository;

use App\Entity\EntretienDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EntretienDate>
 *
 * @method EntretienDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntretienDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntretienDate[]    findAll()
 * @method EntretienDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntretienDateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntretienDate::class);
    }

//    /**
//     * @return EntretienDate[] Returns an array of EntretienDate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EntretienDate
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
