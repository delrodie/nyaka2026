<?php

namespace App\Repository\Main;

use App\Entity\Main\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participant>
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function getAllByStatusCompletedOrNot($status = null, $order = null)
    {
        $query = $this->globalSelect();
        if ($status) {
            $query->where('p.waveCheckoutStatus = :status');
        } else {
            $query->where('p.waveCheckoutStatus <> :status');
        }

        if ($order) $query->orderBy('p.waveWhenCompleted', $order);

        return $query->setParameter('status', 'complete')
            ->getQuery()->getResult();


    }

    public function getAllByGrade($grade, string $status)
    {
        $query = $this->globalSelect()
            ->where('g.id = :grade')
            ->setParameter('grade', $grade);

        if ($status){
            $query->andWhere('p.waveCheckoutStatus = :status')
                ->setParameter('status', $status);
        }
        return $query->getQuery()->getResult();
    }

    public function getAllByVicariat($vicariat, $status = null): mixed
    {
        $query = $this->globalSelect()
            ->where('v.id = :vicariat')
            ->setParameter('vicariat', $vicariat);

        if ($status) {
            $query->andWhere('p.waveCheckoutStatus = :status')
                ->setParameter('status', $status);
        }

        return $query->getQuery()->getResult();
    }

    public function getAllByDoyenne($doyenne, $status = null): mixed
    {
        $query = $this->globalSelect()
            ->where('d.id = :doyenne')
            ->setParameter('doyenne', $doyenne);

        if ($status) {
            $query->andWhere('p.waveCheckoutStatus = :status')
                ->setParameter('status', $status);
        }
        return $query->getQuery()->getResult();
    }


    public function getAllBySection($section, $status = null): mixed
    {
        $query = $this->globalSelect()
            ->where('s.id = :section')
            ->setParameter('section', $section);

        if ($status) {
            $query->andWhere('p.waveCheckoutStatus = :status')
                ->setParameter('status', $status);
        }
        return $query->getQuery()->getResult();
    }

    public function getMontantTotal()
    {
        return $this->createQueryBuilder('p')
            ->select('SUM(p.montant)')
            ->where('p.waveCheckoutStatus = :status')
            ->setParameter('status', 'complete')
            ->getQuery()->getSingleScalarResult();
    }


    private function globalSelect()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('s', 'd', 'v', 'g')
            ->leftJoin('p.section', 's')
            ->leftJoin('s.doyenne', 'd')
            ->leftJoin('d.vicariat', 'v')
            ->leftJoin('p.grade', 'g');
    }



    //    /**
    //     * @return Participant[] Returns an array of Participant objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Participant
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
