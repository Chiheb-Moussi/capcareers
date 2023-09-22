<?php

namespace App\Repository;

use App\Entity\IntresstedCandidats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IntresstedCandidats>
 *
 * @method IntresstedCandidats|null find($id, $lockMode = null, $lockVersion = null)
 * @method IntresstedCandidats|null findOneBy(array $criteria, array $orderBy = null)
 * @method IntresstedCandidats[]    findAll()
 * @method IntresstedCandidats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntresstedCandidatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IntresstedCandidats::class);
    }

//    /**
//     * @return IntresstedCandidats[] Returns an array of IntresstedCandidats objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IntresstedCandidats
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
