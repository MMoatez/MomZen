<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\UserRepository;
use App\Repository\RatingRepository;
use App\Entity\User;
use App\Entity\Rating;

#[Route('/reclamation')]
final class ReclamationController extends AbstractController
{
    #[Route('/admin', name: 'app_reclamation_admin_index', methods: ['GET'])]
    //#[IsGranted('ROLE_ADMIN')]
    public function adminIndex(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/admin/index.html.twig', [
            'reclamations' => $reclamationRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/admin/{id}', name: 'app_reclamation_admin_show', methods: ['GET'])]
    //#[IsGranted('ROLE_ADMIN')]
    public function adminShow(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/admin/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    //#[IsGranted('ROLE_USER')]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    //#[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get user with ID 1 from the database
            $user = $userRepository->find(1);
            
            if (!$user) {
                throw new \RuntimeException('Default user with ID 1 not found. Please ensure the user exists in the database.');
            }

            $reclamation->setCreatedAt(new \DateTime());
            $reclamation->setStatus('pending');
            $reclamation->setUser($user);
            
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'Your reclamation has been submitted successfully.');
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
    //#[IsGranted('ROLE_USER')]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    //#[IsGranted('ROLE_USER')]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    //#[IsGranted('ROLE_USER')]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
