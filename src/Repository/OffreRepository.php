<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }
    public function countOffre(){
        return $this->createQueryBuilder('o')
        ->select('COUNT(o.id)')
        ->getQuery()
        ->getSingleScalarResult();
    }
    public function countOffreEnAttente(){
        return $this->createQueryBuilder('o')
        ->select('COUNT(o.id)')
        ->where(
            $this->createQueryBuilder('o')->expr()->orX(
                'o.status = :val',
                'o.status IS NULL'
            )
        )
        ->setParameter('val', 'En attente')
        ->getQuery()
        ->getSingleScalarResult();
    }
   public function countOffreAccepte()
    {
    return $this->createQueryBuilder('o')
    ->select('COUNT(o.id)')
    ->where('o.status = :val')
    ->setParameter('val', 'Accepté')
    ->getQuery()
    ->getSingleScalarResult();
    }
   public function countOffreRefuser()
   {
    return $this->createQueryBuilder('o')
    ->select('COUNT(o.id)')
    ->where('o.status = :val')
    ->setParameter('val', 'Refusé')
    ->getQuery()
    ->getSingleScalarResult();
    }

    public function countOffresByMonthOfYear(int $year)
    {
        return $this->createQueryBuilder('p')
            ->select('SUBSTRING(p.createdAt, 6, 2) as month, COUNT(p.id) as offre_count')
            ->where('SUBSTRING(p.createdAt, 1, 4) = :year')
            ->setParameter('year', $year)
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByTypeContrat()
    {
        return $this->createQueryBuilder('o')
            ->select('o.typeContrat, COUNT(o.id) as contrat_count')
            ->groupBy('o.typeContrat')
            ->orderBy('contrat_count', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countBySecteur()
    {
        return $this->createQueryBuilder('o')
            ->select('s.titre, COUNT(o.id) as offre_count')
            ->join('o.secteur', 's')  // Perform the join with the secteur table
            ->groupBy('s.titre')
            ->orderBy('offre_count', 'DESC')
            ->getQuery()
            ->getResult();
    }
   
//    /**
//     * @return Offre[] Returns an array of Offre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
