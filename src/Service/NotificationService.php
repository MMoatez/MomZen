<?php

namespace App\Service;

use App\Entity\Notification;
use App\Entity\Reclamation;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * Create a notification for all admin users when a new reclamation is created
     */
    public function createReclamationNotification(Reclamation $reclamation): void
    {
        // Get all admin users
        $admins = $this->userRepository->findByRole('ROLE_ADMIN');
        
        if (empty($admins)) {
            return;
        }
        
        $user = $reclamation->getUser();
        $userName = $user ? $user->getPrenom() . ' ' . $user->getNom() : 'An user';
        $message = sprintf('%s has submitted a new reclamation: "%s"', $userName, $reclamation->getTitle());
        
        foreach ($admins as $admin) {
            $notification = new Notification();
            $notification->setMessage($message);
            $notification->setCreatedAt(new \DateTime());
            $notification->setIsRead(false);
            $notification->setAdmin($admin);
            $notification->setReclamation($reclamation);
            $notification->setType('reclamation_new');
            
            $this->entityManager->persist($notification);
        }
        
        $this->entityManager->flush();
    }
} 