<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Consultation;
use App\Entity\Dossiermedical;
use App\Form\ConsultationType;
use App\Service\PdfService;
final class ConsultationController extends AbstractController
{
    private PdfService $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    #[Route('/consultation/{id}/pdf', name: 'app_consultation_pdf')]
    public function generatePdf(Consultation $consultation): Response
    {
        // Vérifier si l'ordonnance existe
        if (!$consultation->getOrdonnance()) {
            $this->addFlash('error', 'Aucune ordonnance disponible pour cette consultation.');
            return $this->redirectToRoute('app_consultation_index');
        }

        // Contenu du PDF
        $htmlContent = $this->renderView('consultation/pdf_template.html.twig', [
            'consultation' => $consultation,
        ]);

        // Générer le PDF
return $this->pdfService->generatePdf(
            'consultation/pdf_template.html.twig',
            ['consultation' => $consultation],
            'ordonnance.pdf'
        );
        }




    #[Route('/consultations', name: 'app_consultation', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $consultations = $entityManager->getRepository(Consultation::class)->findAll();

        return $this->render('consultation/index.html.twig', [
            'consultations' => $consultations,
        ]);
    }
    #[Route('/{id}/delete', name: 'app_consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_consultation');
    }
    #[Route('/{id}/edit', name: 'app_consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_consultation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/consultation/{id<\d+>}', name: 'app_consultation_show')]
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    #[Route('/new/{dossierId}', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(int $dossierId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossiermedical = $entityManager->getRepository(Dossiermedical::class)->find($dossierId);

        if (!$dossiermedical) {
            throw $this->createNotFoundException('Dossier médical introuvable.');
        }

        $consultation = new Consultation();
        $consultation->setDossiermedical($dossiermedical);
        $consultation->setMedecin($this->getUser()); // Set current logged-in doctor

        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($consultation);
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }
}
