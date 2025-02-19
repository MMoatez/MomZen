<?php

namespace App\Controller;

use App\Entity\DossierMedicall;
use App\Form\DossierMedicallType;
use App\Repository\DossierMedicallRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dossier_medical')]
final class DossierMedicallController extends AbstractController{
    #[Route(name: 'app_dossier_medical_index', methods: ['GET'])]
    public function index(DossierMedicallRepository $dossierMedicallRepository): Response
    {
        return $this->render('dossier_medical/index.html.twig', [
            'dossier_medicals' => $dossierMedicallRepository->findAll(),
        ]);
    }
    #[Route(name: 'app_dossier_medical_index_admin', methods: ['GET'])]
    public function indexadmin(DossierMedicallRepository $dossierMedicallRepository): Response
    {
        return $this->render('dossier_medical/indexadmin.html.twig', [
            'dossier_medicals' => $DossierMedicallRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_dossier_medical_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossierMedicall = new DossierMedicall();
        $dossierMedicall->setDatecreation(new \DateTime());

        $form = $this->createForm(DossierMedicallType::class, $dossierMedicall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dossierMedicall);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier_medical/new.html.twig', [
            'dossier_medical' => $dossierMedicall,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_medical_show', methods: ['GET'])]
    public function show(DossierMedicall $dossierMedicall): Response
    {
        return $this->render('dossier_medical/show.html.twig', [
            'dossier_medical' => $dossierMedicall,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dossier_medical_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DossierMedicall $dossierMedicall, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossierMedicallType::class, $dossierMedicall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier_medical/edit.html.twig', [
            'dossier_medical' => $dossierMedicall,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit/admin', name: 'app_dossier_medical_edit_admin', methods: ['GET', 'POST'])]
    public function editadmin(Request $request, DossierMedicall $dossierMedicall, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossierMedicallType::class, $dossierMedicall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_medical_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier_medical/editadmin.html.twig', [
            'dossier_medical' => $dossierMedicall,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dossier_medical_delete', methods: ['POST'])]
    public function delete(Request $request, DossierMedicall $dossierMedicall, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($dossierMedicall);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_dossier_medical_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/admin', name: 'app_dossier_medical_delete_admin', methods: ['POST'])]
    public function deleteadmin(Request $request, DossierMedicall $dossierMedicall, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($dossierMedicall);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_dossier_medical_index_admin', [], Response::HTTP_SEE_OTHER);
    }
}
