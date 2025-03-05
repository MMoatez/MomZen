<?php
// src/Controller/DemandeAmbulanceController.php
namespace App\Controller;

use App\Entity\DemandeAmbulance;
use App\Repository\DemandeAmbulanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted; // Importez IsGranted

#[IsGranted('ROLE_ADMIN')] // Restreint l'accès à tout le contrôleur
#[Route('/demandes-ambulance')]
class DemandeAmbulanceController extends AbstractController
{
    #[Route('/', name: 'app_demande_ambulance_index', methods: ['GET'])]
    public function index(DemandeAmbulanceRepository $demandeAmbulanceRepository): Response
    {
        return $this->render('demande_ambulance/index.html.twig', [
            'demande_ambulances' => $demandeAmbulanceRepository->findRecentDemandes(),
        ]);
    }
    #[Route('/new', name: 'app_demande_ambulance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demande = new DemandeAmbulance();
        $demande->setCreatedAt(new \DateTime());

        $form = $this->createForm(DemandeAmbulanceType::class, $demande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demande);
            $entityManager->flush();

            return $this->redirectToRoute('app_demande_ambulance_index');
        }

        return $this->render('demande_ambulance/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/confirmer', name: 'app_demande_ambulance_confirmer', methods: ['POST'])]
    public function confirmer(Request $request, DemandeAmbulance $demandeAmbulance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('confirmer' . $demandeAmbulance->getId(), $request->request->get('_token'))) {
            $demandeAmbulance->setStatut('confirmée');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_ambulance_index');
    }

    #[Route('/{id}/annuler', name: 'app_demande_ambulance_annuler', methods: ['POST'])]
    public function annuler(Request $request, DemandeAmbulance $demandeAmbulance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('annuler' . $demandeAmbulance->getId(), $request->request->get('_token'))) {
            $demandeAmbulance->setStatut('annulée');
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_demande_ambulance_index');
    }
}