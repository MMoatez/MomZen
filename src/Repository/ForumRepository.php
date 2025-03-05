<?php

namespace App\Repository;

use App\Entity\Forum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forum>
 */
class ForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forum::class);
    }
    public function findBySearchAndSort(?string $search = null, string $sort = 'date_desc'): array
{
    $qb = $this->createQueryBuilder('f');

    if ($search) {
        $qb->andWhere('f.titre LIKE :search')
           ->setParameter('search', '%'.$search.'%');
    }

    // Gestion du tri
    $order = ($sort === 'date_asc') ? 'ASC' : 'DESC';
    $qb->orderBy('f.datePublication', $order);

    return $qb->getQuery()->getResult();
}
public function findAllSortedByDate(): array
{
    return $this->createQueryBuilder('f')
        ->orderBy('f.datePublication', 'DESC')
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Forum[] Returns an array of Forum objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Forum
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
