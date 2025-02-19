<?php

namespace App\Controller;
use App\Entity\DossierMedicall;
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
    #[Route('/dossier_back', name: 'app_back_dossier_medical_index_admin')]
    public function indexadmin()
    {
        // Récupérer les forums depuis la base de données
        $dossier_medicals = $this->entityManager->getRepository(DossierMedicall::class)->findAll();

  
        return $this->render('dossier_back/index.html.twig', [
            'dossier_medicals' => $dossier_medicals,
        ]);
    }
    /*#[Route('/dossier_back', 'app_back_dossier_medical_index_admin', methods: ['GET'])]
    public function indexadmin(DossierMedicallRepository $dossierMedicallRepository): Response
    {
        return $this->render('dossier_back/index.html.twig', [
            'dossier_medicals' => $DossierMedicallRepository->findAll(),
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
