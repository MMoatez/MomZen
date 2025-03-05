<?php

namespace App\Repository;

use App\Entity\Ambulance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ambulance>
 */
class AmbulanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ambulance::class);
    }
    public function findBySearchAndSort(string $search = '', string $sortBy = 'id', string $order = 'asc'): array
{
    $qb = $this->createQueryBuilder('a');

    if (!empty($search)) {
        $qb->andWhere('a.immatriculation LIKE :search 
            OR a.marque LIKE :search 
            OR a.modele LIKE :search')
           ->setParameter('search', '%' . $search . '%');
    }

    $qb->orderBy('a.' . $sortBy, $order);

    return $qb->getQuery()->getResult();
}
//    /**
//     * @return Ambulance[] Returns an array of Ambulance objects
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

//    public function findOneBySomeField($value): ?Ambulance
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
