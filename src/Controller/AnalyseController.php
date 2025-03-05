<?php

namespace App\Controller;

use App\Entity\Analyse;
use App\Form\AnalyseType;
use App\Entity\DossierMedicall;
use App\Repository\AnalyseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/analyse')]
final class AnalyseController extends AbstractController{
    #[Route(name: 'app_analyse_index', methods: ['GET'])]
    public function index(AnalyseRepository $analyseRepository): Response
    {
        return $this->render('analyse/index.html.twig', [
            'analyses' => $analyseRepository->findAll(),
        ]);
    }

    #[Route('/dossier-medical/{id}/analyse', name: 'app_dossier_medical_analyse')]
    public function analyse(DossierMedicall $dossierMedicall, EntityManagerInterface $entityManager): Response
    {
        // Génération automatique de l'analyse si elle n'existe pas
        $analyse = $entityManager->getRepository(Analyse::class)->findOneBy(['dossier_medicale' => $dossierMedicall]);
        
        if (!$analyse) {
            $analyse = new Analyse();
            $analyse->setDossierMedicale($dossierMedicall);
            
            // Calcul du risque
            $semaines = $dossierMedicall->getGrossesSemaine();
            $analyse->setRisqueGrosses(match(true) {
                $semaines >= 40 => 'Risque élevé',
                $semaines >= 30 => 'Risque modéré',
                default => 'Risque normal'
            });
    
            // Détermination urgence
            $symptomes = strtolower($dossierMedicall->getSymptotes());
            $urgence = str_contains($symptomes, 'douleur intense') 
                    || str_contains($symptomes, 'saignement')
                    || str_contains($symptomes, 'contractions');
            $analyse->setUrgenceMedicale($urgence);
    
            $entityManager->persist($analyse);
            $entityManager->flush();
        }
    
        $analyses = [$analyse]; // Tableau pour garder la structure existante
    
        return $this->render('dossier_medical/analyse.html.twig', [
            'dossier_medical' => $dossierMedicall,
            'analyses' => $analyses,
        ]);
    }

    #[Route('/dossier-medical/{id}/analyse/admin', name: 'app_dossier_medical_analyse_admin')]
    public function analyseadmin(DossierMedicall $dossierMedicall,EntityManagerInterface $entityManager): Response
    {
        // Ici vous pouvez obtenir toutes les analyses liées à ce dossier médical
        // Par exemple, en utilisant un repository pour récupérer les analyses d'un dossier médical :
        $analyses = $entityManager->getRepository(Analyse::class)->findBy(['dossier_medicale' => $dossierMedical]);

        return $this->render('dossier_medical/analyseadmin.html.twig', [
            'dossier_medical' => $dossierMedicall,
            'analyses' => $analyses,
        ]);
    }

    #[Route('/new', name: 'app_analyse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $analyse = new Analyse();
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($analyse);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('analyse/new.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_analyse_show', methods: ['GET'])]
    public function show(Analyse $analyse): Response
    {
        return $this->render('analyse/show.html.twig', [
            'analyse' => $analyse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_analyse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('analyse/edit.html.twig', [
            'analyse' => $analyse,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_analyse_delete', methods: ['POST'])]
    public function delete(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($analyse);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/admin/{id}/admin', name: 'admin_app_analyse_delete', methods: ['POST'])]
    public function deleteadmin(Request $request, Analyse $analyse, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($analyse);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_dossier_medical_index_admin', [], Response::HTTP_SEE_OTHER);
    }
}
