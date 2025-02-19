<?php
// src/Controller/VoyageController.php
namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
#[Route('/voyage')]
class VoyageController extends AbstractController
{
    
    // Afficher la liste des voyages
    #[Route('/', name: 'app_voyage_index', methods: ['GET'])]
    public function index(VoyageRepository $voyageRepository, LoggerInterface $logger): Response
{
    $voyages = $voyageRepository->findAll();

    // Log the first 5 voyages
    foreach (array_slice($voyages, 0, 5) as $voyage) {
        $logger->info('Voyage', [
            'id' => $voyage->getId(),
            'date_depart' => $voyage->getDateDepart(),
            'emplacement_client' => $voyage->getEmplacementClient(),
            'ambulance' => $voyage->getAmbulance(),
        ]);
    }

    return $this->render('voyage/index.html.twig', [
        'voyages' => $voyages,
    ]);
}

    // Ajouter un nouveau voyage
    #[Route('/new', name: 'app_voyage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $voyage = new Voyage();
    $form = $this->createForm(VoyageType::class, $voyage);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
        dd($form->getData()); // Affiche les données du formulaire
    }

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($voyage);
        $entityManager->flush();

        return $this->redirectToRoute('app_voyage_index');
    }

    return $this->render('voyage/new.html.twig', [
        'voyage' => $voyage,
        'form' => $form->createView(),
    ]);
}

    // Afficher les détails d'un voyage
    #[Route('/{id}', name: 'app_voyage_show', methods: ['GET'])]
    public function show(Voyage $voyage): Response
    {
        return $this->render('voyage/show.html.twig', [
            'voyage' => $voyage,
        ]);
    }

    // Modifier un voyage
    #[Route('/{id}/edit', name: 'app_voyage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_index');
        }

        return $this->render('voyage/edit.html.twig', [
            'voyage' => $voyage,
            'form' => $form->createView(),
        ]);
    }

    // Supprimer un voyage
    #[Route('/{id}', name: 'app_voyage_delete', methods: ['POST'])]
    public function delete(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($voyage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyage_index');
    }
}