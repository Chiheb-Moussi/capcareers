<?php

namespace App\Repository;

use App\Entity\CandidatInfoSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CandidatInfoSkill>
 *
 * @method CandidatInfoSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidatInfoSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidatInfoSkill[]    findAll()
 * @method CandidatInfoSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatInfoSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidatInfoSkill::class);
    }

//    /**
//     * @return CandidatInfoSkill[] Returns an array of CandidatInfoSkill objects
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

//    public function findOneBySomeField($value): ?CandidatInfoSkill
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
