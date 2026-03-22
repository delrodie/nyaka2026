<?php

namespace App\Repository\List;

use App\Entity\List\Adhesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adhesion>
 */
class AdhesionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adhesion::class);
    }

    public function findByDoyenneAndMembre(string $membre, string $doyenne)
    {
        return $this->query()
            ->where('a.nomPrenoms LIKE :membre')
            ->andWhere('d.id = :doyenne')
            ->setParameter('membre', '%'.$membre.'%')
            ->setParameter('doyenne', $doyenne)
            ->getQuery()->getResult()
            ;
    }

    public function query()
    {
        return $this->createQueryBuilder('a')
            ->addSelect('g', 's', 'd', 'v')
            ->innerJoin('a.grade', 'g')
            ->innerJoin('a.section', 's')
            ->innerJoin('s.doyenne', 'd')
            ->innerJoin('d.vicariat', 'v')
            ;
    }

    //    /**
    //     * @return Adhesion[] Returns an array of Adhesion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Adhesion
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
