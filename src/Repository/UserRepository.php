<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findBySearchTerm(string $searchTerm): array
{
    return $this->createQueryBuilder('u')
        ->where('u.nom LIKE :searchTerm OR u.prenom LIKE :searchTerm OR u.numTel LIKE :searchTerm')
        ->setParameter('searchTerm', '%' . $searchTerm . '%')
        ->orderBy('u.nom', 'ASC')
        ->getQuery()
        ->getResult();
}



public function findAllSortedByNom(string $order = 'asc'): array
{
    return $this->createQueryBuilder('u')
        ->orderBy('u.nom', $order)
        ->getQuery()
        ->getResult();
}

/**
 * Find users by role
 * 
 * @param string $role The role to search for
 * @return User[] Returns an array of User objects with the specified role
 */
public function findByRole(string $role): array
{
    $qb = $this->createQueryBuilder('u');
    
    return $qb->where($qb->expr()->like('u.roles', ':role'))
        ->setParameter('role', '%"'.$role.'"%')
        ->getQuery()
        ->getResult();
}
}
