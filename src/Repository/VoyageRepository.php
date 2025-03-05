<?php

namespace App\Repository;

use App\Entity\Voyage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voyage>
 */
class VoyageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voyage::class);
    }
    public function findAllWithAmbulance()
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.ambulance', 'a')
            ->addSelect('a')
            ->getQuery()
            ->getResult();
    }
    public function findBySearchAndSort(string $search = '', string $sortBy = 'id', string $order = 'asc'): array
{
    $queryBuilder = $this->createQueryBuilder('v');

    // Ajouter une condition de recherche si un terme est fourni
    if ($search) {
        $queryBuilder->andWhere('v.emplacement_client LIKE :search')
            ->setParameter('search', '%' . $search . '%');
    }

    // Trier les résultats
    $queryBuilder->orderBy('v.' . $sortBy, $order);

    return $queryBuilder->getQuery()->getResult();
}
//    /**
//     * @return Voyage[] Returns an array of Voyage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Voyage
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
