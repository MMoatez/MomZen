<?php

namespace App\Controller;

use App\Entity\Ambulance;
use App\Form\Ambulance1Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AmbulanceRepository;
#[Route('/ambulance')]
final class AmbulanceController extends AbstractController{
    #[Route(name: 'app_ambulance_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $ambulances = $entityManager
            ->getRepository(Ambulance::class)
            ->findAll();

        return $this->render('ambulance/index.html.twig', [
            'ambulances' => $ambulances,
        ]);
    }

    #[Route('/backambulance',name: 'app_ambulance_', methods: ['GET'])]
    public function indexxx(EntityManagerInterface $entityManager): Response
    {
        $ambulances = $entityManager
            ->getRepository(Ambulance::class)
            ->findAll();

        return $this->render('ambulance/backindex.html.twig', [
            'ambulances' => $ambulances,
        ]);
    }
    public function list(AmbulanceRepository $ambulanceRepository): Response
    {
        // Fetch all ambulances from the repository
        $ambulances = $ambulanceRepository->findAll();

        // Pass the ambulances variable to the template
        return $this->render('home_back/ambulance_back.html.twig', [
            'ambulances' => $ambulances,
        ]);
    }
    #[Route('/back/ambulance', name: 'app_back_ambulance_index')]
    public function indexx(EntityManagerInterface $entityManager): Response
    {
        // Récupérer la liste des ambulances depuis la base de données
        $ambulances = $entityManager->getRepository(Ambulance::class)->findAll();

        return $this->render('back/ambulance/index.html.twig', [
            'ambulances' => $ambulances,
        ]);
    }

    #[Route('/new', name: 'app_ambulance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ambulance = new Ambulance();
        $form = $this->createForm(Ambulance1Type::class, $ambulance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ambulance);
            $entityManager->flush();

            return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ambulance/new.html.twig', [
            'ambulance' => $ambulance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ambulance_show', methods: ['GET'])]
    public function show(Ambulance $ambulance): Response
    {
        return $this->render('ambulance/show.html.twig', [
            'ambulance' => $ambulance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ambulance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ambulance $ambulance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Ambulance1Type::class, $ambulance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ambulance/edit.html.twig', [
            'ambulance' => $ambulance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ambulance_delete', methods: ['POST'])]
    public function delete(Request $request, Ambulance $ambulance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ambulance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ambulance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ambulance_index', [], Response::HTTP_SEE_OTHER);
    }
    public function gestion(): Response
    {
        // Logique pour afficher la gestion des ambulances
        return $this->render('ambulance/gestion.html.twig');
    }
   
    #[Route('/ambulance', name: 'app_ambulance_backindex')]
    public function index_back(EntityManagerInterface $entityManager): Response
    {
        $ambulances = $entityManager->getRepository(Ambulance::class)->findAll();

        // Votre logique ici
        return $this->render('ambulance/index.html.twig', [
            'ambulances' => $ambulances,
        ]);
    }
}

