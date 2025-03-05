<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/notification')]
final class NotificationController extends AbstractController
{
    #[Route('/list', name: 'app_notification_list', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function list(NotificationRepository $notificationRepository): JsonResponse
    {
        $notifications = $notificationRepository->findAllByAdmin($this->getUser(), 10);
        
        $formattedNotifications = [];
        foreach ($notifications as $notification) {
            $formattedNotifications[] = [
                'id' => $notification->getId(),
                'message' => $notification->getMessage(),
                'createdAt' => $notification->getCreatedAt()->format('Y-m-d H:i:s'),
                'isRead' => $notification->isIsRead(),
                'type' => $notification->getType(),
                'reclamationId' => $notification->getReclamation() ? $notification->getReclamation()->getId() : null,
            ];
        }
        
        return $this->json([
            'notifications' => $formattedNotifications,
            'unreadCount' => $notificationRepository->countUnreadByAdmin($this->getUser()),
        ]);
    }
    
    #[Route('/mark-read/{id}', name: 'app_notification_mark_read', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function markAsRead(Notification $notification, EntityManagerInterface $entityManager): JsonResponse
    {
        // Check if the notification belongs to the current user
        if ($notification->getAdmin() !== $this->getUser()) {
            return $this->json(['success' => false, 'message' => 'Not authorized'], Response::HTTP_FORBIDDEN);
        }
        
        $notification->setIsRead(true);
        $entityManager->flush();
        
        return $this->json(['success' => true]);
    }
    
    #[Route('/mark-all-read', name: 'app_notification_mark_all_read', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function markAllAsRead(NotificationRepository $notificationRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $unreadNotifications = $notificationRepository->findUnreadByAdmin($this->getUser());
        
        foreach ($unreadNotifications as $notification) {
            $notification->setIsRead(true);
        }
        
        $entityManager->flush();
        
        return $this->json(['success' => true]);
    }
} 