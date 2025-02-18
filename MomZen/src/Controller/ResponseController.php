<?php

namespace App\Controller;

use App\Entity\Response;
use App\Entity\Reclamation;
use App\Form\ResponseType;
use App\Repository\ResponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;
#[Route('/admin/response')]
//#[IsGranted('ROLE_ADMIN')]
class ResponseController extends AbstractController
{
    #[Route('/reclamation/{id}/new', name: 'app_response_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Reclamation $reclamation): HttpResponse
    {
        $response = new Response();
        $response->setReclamation($reclamation);
        
        // Create a temporary admin user with ID 1
        $admin = new User();
        $admin->setEmail("admin@example.com");
        $admin->setNom("Admin");
        $admin->setPrenom("User");
        $admin->setPassword("admin123");
        $admin->setNumTel(12345678);
        $admin->setGenre("MALE");
        $admin->setImage("admin.jpg");
        
        $response->setAdmin($admin);

        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatus('answered');
            $entityManager->persist($admin);
            $entityManager->persist($response);
            $entityManager->flush();

            $this->addFlash('success', 'Response has been added successfully');
            return $this->redirectToRoute('app_reclamation_admin_show', ['id' => $reclamation->getId()]);
        }

        return $this->render('response/new.html.twig', [
            'response' => $response,
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_response_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Response $response, EntityManagerInterface $entityManager): HttpResponse
    {
        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Response has been updated successfully');
            return $this->redirectToRoute('app_reclamation_admin_show', ['id' => $response->getReclamation()->getId()]);
        }

        return $this->render('response/edit.html.twig', [
            'response' => $response,
            'reclamation' => $response->getReclamation(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_response_delete', methods: ['POST'])]
    public function delete(Request $request, Response $response, EntityManagerInterface $entityManager): HttpResponse
    {
        if ($this->isCsrfTokenValid('delete'.$response->getId(), $request->getPayload()->getString('_token'))) {
            $reclamation = $response->getReclamation();
            $entityManager->remove($response);
            
            // If this was the last response, update reclamation status back to pending
            if ($reclamation->getResponses()->count() <= 1) {
                $reclamation->setStatus('pending');
            }
            
            $entityManager->flush();
            $this->addFlash('success', 'Response has been deleted successfully');
        }

        return $this->redirectToRoute('app_reclamation_admin_show', ['id' => $response->getReclamation()->getId()]);
    }
} 