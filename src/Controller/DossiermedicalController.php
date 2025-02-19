<?php

namespace App\Controller;

use App\Entity\Dossiermedical;
use App\Entity\Rendezvous;
use App\Entity\User;

use App\Form\DossiermedicalType;
use App\Repository\DossiermedicalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\RendezvousRepository;

    #[Route('/dossiermedical')]
    final class DossiermedicalController extends AbstractController
    {
    
        #[Route('/dossiermedical/{patientId}/{rendezvousId}', name: 'app_dossiermedical_index', methods: ['GET'])]
        public function dossierMedical($rendezvousId, $patientId, EntityManagerInterface $entityManager, RendezvousRepository $rendezvousRepository): Response
        {
            // Récupérer les objets Rendezvous et Patient depuis la base de données
            $rendezvous = $entityManager->getRepository(Rendezvous::class)->find($rendezvousId);
            $patient = $entityManager->getRepository(User::class)->find($patientId);
        
            // Vérifier si le rendez-vous et le patient existent
            if (!$rendezvous) {
                throw $this->createNotFoundException('Rendez-vous non trouvé');
            }
        
            if (!$patient) {
                throw $this->createNotFoundException('Patient non trouvé');
            }
        
            // Récupérer le dossier médical du patient (si existe)
            $dossiermedical = $entityManager->getRepository(Dossiermedical::class)->findOneBy(['idpatient' => $patient]);
        
                // Si le dossier médical n'existe pas, rediriger vers la page de création
            if (!$dossiermedical) {
                return $this->redirectToRoute('app_dossiermedical_create', [
                    'patientId' => $patientId, // Passer l'ID du patient
                    'rendezvousId' => $rendezvousId,

                     // Passer l'ID du rendez-vous
                ]);
            }

    
            $dossiermedicals = $entityManager->getRepository(Dossiermedical::class)->findBy(['idpatient' => $patient]);
            if ($rendezvousId) {
                $rendezvous = $rendezvousRepository->find($rendezvousId);
                if ($rendezvous && !$rendezvous->isRealise()) {
                    $rendezvous->setRealise(true);
                    $entityManager->flush(); 
                }
            }
            return $this->render('dossiermedical/index.html.twig', [
                'dossiermedicals' => $dossiermedicals,
                'patientId' => $patientId,
                'rendezvousId' => $rendezvousId,
            ]);  
        }
        #[Route('/create/{patientId}/{rendezvousId}', name: 'app_dossiermedical_create', methods: ['GET', 'POST'])]
        public function create(Request $request, $patientId, $rendezvousId, EntityManagerInterface $entityManager): Response
        {
            // Récupérer le patient et le rendez-vous depuis la base de données
            $patient = $entityManager->getRepository(User::class)->find($patientId);
            $rendezvous = $entityManager->getRepository(Rendezvous::class)->find($rendezvousId);
        
            if (!$patient) {
                throw $this->createNotFoundException('Patient non trouvé');
            }
        
            if (!$rendezvous) {
                throw $this->createNotFoundException('Rendez-vous non trouvé');
            }
        
            // Créer un nouveau dossier médical pour ce patient
            $dossiermedical = new Dossiermedical();
            $dossiermedical->setIdpatient($patient);
        
            // Créer et gérer le formulaire pour le dossier médical
            $form = $this->createForm(DossiermedicalType::class, $dossiermedical);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($dossiermedical);
                $entityManager->flush();
        
                // Rediriger vers la page du dossier médical après la création
                return $this->redirectToRoute('app_dossiermedical_index', [
                    'patientId' => $patientId, // Passer l'ID du patient
                    'rendezvousId' => $rendezvousId,
                    // Passer l'ID du rendez-vous
                ]);
                
            }
        
            return $this->render('dossiermedical/new.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        
  

    #[Route('/new', name: 'app_dossiermedical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossiermedical = new Dossiermedical();
        $form = $this->createForm(DossiermedicalType::class, $dossiermedical);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dossiermedical);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossiermedical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossiermedical/new.html.twig', [
            'dossiermedical' => $dossiermedical,
            'form' => $form,
        ]);
    }

    #[Route('/{patientId}/{rendezvousId}/{id}', name: 'app_dossiermedical_show', methods: ['GET'])]
    public function show($patientId, $rendezvousId, Dossiermedical $dossiermedical): Response
    {
        return $this->render('dossiermedical/show.html.twig', [
            'dossiermedical' => $dossiermedical,
            'patientId' => $patientId,
            'rendezvousId' => $rendezvousId
        ]);
    }
    

    #[Route('/{patientId}/{rendezvousId}/{id}/edit', name: 'app_dossiermedical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $patientId, $rendezvousId, Dossiermedical $dossiermedical, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossiermedicalType::class, $dossiermedical);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_dossiermedical_index', [
                'patientId' => $patientId,
                'rendezvousId' => $rendezvousId
            ], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('dossiermedical/edit.html.twig', [
            'dossiermedical' => $dossiermedical,
            'form' => $form,
            'patientId' => $patientId,
            'rendezvousId' => $rendezvousId
        ]);
    }
    

    #[Route('/{patientId}/{rendezvousId}/{id}/delete', name: 'app_dossiermedical_delete', methods: ['POST'])]
    public function delete(Request $request, $patientId, $rendezvousId, Dossiermedical $dossiermedical, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dossiermedical->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dossiermedical);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_dossiermedical_index', [
            'patientId' => $patientId,
            'rendezvousId' => $rendezvousId
        ], Response::HTTP_SEE_OTHER);
    }
    
          

  /*
    #[Route('/dossiermedical', name: 'app_dossiermedical_index', methods: ['GET'])]
public function index(DossiermedicalRepository $dossiermedicalRepository, Request $request, EntityManagerInterface $entityManager, RendezvousRepository $rendezvousRepository): Response
{
    // Vérifier si un rendez-vous doit être marqué comme réalisé
    $rendezvousId = $request->query->get('rendezvousId');

    if ($rendezvousId) {
        $rendezvous = $rendezvousRepository->find($rendezvousId);
        if ($rendezvous && !$rendezvous->isRealise()) {
            $rendezvous->setRealise(true);
            $entityManager->flush(); // ✅ Mise à jour en base de données
        }
    }

    return $this->render('dossiermedical/index.html.twig', [
        'dossiermedicals' => $dossiermedicalRepository->findAll(),
    ]);
}*/



    /*
    #[Route(name: 'app_dossiermedical_index', methods: ['GET'])]
    public function index(DossiermedicalRepository $dossiermedicalRepository): Response
    {
        return $this->render('dossiermedical/index.html.twig', [
            'dossiermedicals' => $dossiermedicalRepository->findAll(),
        ]);
    }*/
}
