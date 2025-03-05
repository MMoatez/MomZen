<?php

namespace App\Repository;

use App\Entity\DemandeAmbulance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DemandeAmbulanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemandeAmbulance::class);
    }

   
    public function findRecentDemandes(int $maxResults = 10): array
{
    return $this->createQueryBuilder('d')
        ->orderBy('d.dateCreation', 'DESC') // Modification ici
        ->setMaxResults($maxResults)
        ->getQuery()
        ->getResult();
}
}