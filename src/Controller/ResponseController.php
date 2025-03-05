<?php

namespace App\Controller;

use App\Entity\Response;
use App\Entity\Reclamation;
use App\Form\ResponseType;
use App\Repository\ResponseRepository;
use App\Service\ReclamationMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/response')]
#[IsGranted('ROLE_ADMIN')]
class ResponseController extends AbstractController
{
    #[Route('/reclamation/{id}/new', name: 'app_response_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        Reclamation $reclamation,
        ReclamationMailer $reclamationMailer
    ): HttpResponse
    {
        $response = new Response();
        $response->setReclamation($reclamation);
        $response->setAdmin($this->getUser());
        // createdAt and updatedAt are set in the constructor of Response

        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatus('answered');
            $entityManager->persist($response);
            $entityManager->flush();

            // Send email notification to the user
            $reclamationMailer->sendResponseNotification($response);

            $this->addFlash('success', 'Response has been added successfully and notification email sent to the user');
            return $this->redirectToRoute('app_reclamation_admin_show', ['id' => $reclamation->getId()]);
        }

        return $this->render('response/new.html.twig', [
            'response' => $response,
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_response_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Response $response, 
        EntityManagerInterface $entityManager,
        ReclamationMailer $reclamationMailer
    ): HttpResponse
    {
        $form = $this->createForm(ResponseType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // updatedAt is automatically updated via the PreUpdate lifecycle callback
            $entityManager->flush();

            // Send email notification about the updated response
            $reclamationMailer->sendResponseNotification($response);

            $this->addFlash('success', 'Response has been updated successfully and notification email sent to the user');
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