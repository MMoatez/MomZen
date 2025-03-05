<?php

// src/Controller/VoyageController.php
namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoyageController extends AbstractController
{
    #[Route('/voyage/new', name: 'app_voyage_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voyage);
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_index');
        }

        return $this->render('voyage/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/voyage', name: 'app_voyage_index', methods: ['GET'])]
public function index(Request $request, VoyageRepository $voyageRepository): Response
{
    // Récupérer les paramètres de recherche et de tri
    $search = $request->query->get('search', '');
    $sortBy = $request->query->get('sort_by', 'id'); // Par défaut, tri par ID
    $order = $request->query->get('order', 'asc'); // Par défaut, ordre ascendant

    // Appeler la méthode du repository pour filtrer et trier
    $voyages = $voyageRepository->findBySearchAndSort($search, $sortBy, $order);

    return $this->render('voyage/index.html.twig', [
        'voyages' => $voyages,
        'search' => $search,
        'sort_by' => $sortBy,
        'order' => $order,
    ]);
}
}