<?php

namespace App\Controller;

use App\Entity\Forum;
use Doctrine\ORM\EntityManagerInterface; // Assurez-vous que cette ligne est présente
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


final class HomeBackController extends AbstractController{
    #[Route('admin', name: 'app_home_back')]
    public function index(): Response
    {
        return $this->render('home_back/index.html.twig', [
            'controller_name' => 'HomeBackController',
        ]);
    }
  
    private $entityManager;

    // Injection du EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;  // Affectation du EntityManager à la propriété de la classe
    }

    #[Route('/forumback', name: 'app_forum_backindex')]
    public function forum()
    {
        // Récupérer les forums depuis la base de données
        $forums = $this->entityManager->getRepository(Forum::class)->findAll();

        // Rendre le template avec la variable 'forums'
        return $this->render('home_back/forum.html.twig', [
            'forums' => $forums,
        ]);
    }

 
/*
    #[Route('user', name: 'app_user')]
    public function user(): Response
    {
        return $this->render('home_back/user.html.twig', [
            'controller_name' => 'HomeBackController',
        ]);
    }
        */
}
