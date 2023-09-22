<?php

namespace App\Repository;

use App\Entity\CandidatInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CandidatInfo>
 *
 * @method CandidatInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidatInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidatInfo[]    findAll()
 * @method CandidatInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidatInfo::class);
    }

//    /**
//     * @return CandidatInfo[] Returns an array of CandidatInfo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CandidatInfo
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
