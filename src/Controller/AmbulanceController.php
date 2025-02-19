<?php
// src/Controller/AmbulanceController.php
namespace App\Controller;

use App\Entity\Ambulance;
use App\Form\AmbulanceType;
use App\Repository\AmbulanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ambulance')] // Définir un préfixe commun pour toutes les routes
class AmbulanceController extends AbstractController
{
    // Afficher la liste des ambulances
    #[Route('/', name: 'app_ambulance_index')] // Route pour afficher la liste
    public function index(AmbulanceRepository $ambulanceRepository): Response
    {
        return $this->render('ambulance/index.html.twig', [
            'ambulances' => $ambulanceRepository->findAll(),
        ]);
    }

    // Ajouter une nouvelle ambulance
    #[Route('/new', name: 'app_ambulance_new', methods: ['GET', 'POST'])] // Définir la route "new" après le préfixe /ambulance
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $ambulance = new Ambulance();
        $form = $this->createForm(AmbulanceType::class, $ambulance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ambulance);
            $em->flush();

            return $this->redirectToRoute('app_ambulance_index');
        }

        return $this->render('ambulance/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Afficher les détails d'une ambulance
    #[Route('/{id}', name: 'app_ambulance_show', methods: ['GET'])] // Afficher une ambulance spécifique
    public function show(Ambulance $ambulance): Response
    {
        return $this->render('ambulance/show.html.twig', [
            'ambulance' => $ambulance,
        ]);
    }

    // Modifier une ambulance
    #[Route('/{id}/edit', name: 'app_ambulance_edit', methods: ['GET', 'POST'])] // Modifier une ambulance
    public function edit(Request $request, Ambulance $ambulance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AmbulanceType::class, $ambulance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ambulance_index');
        }

        return $this->render('ambulance/edit.html.twig', [
            'ambulance' => $ambulance,
            'form' => $form->createView(),
        ]);
    }

    // Supprimer une ambulance
    #[Route('/{id}', name: 'app_ambulance_delete', methods: ['POST'])] // Route de suppression
    public function delete(Request $request, Ambulance $ambulance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ambulance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ambulance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ambulance_index');
    }
}

