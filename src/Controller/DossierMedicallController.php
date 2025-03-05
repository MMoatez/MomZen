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
use Knp\Snappy\Pdf;
use TCPDF;


#[Route('/dossier_medical')]
final class DossierMedicallController extends AbstractController{
    // Dans DossierMedicallController.php
#[Route('/', name: 'app_dossier_medical_index')]
public function index(Request $request, EntityManagerInterface $em): Response
{
    $sort = $request->query->get('sort', 'datecreation');
    $direction = $request->query->get('direction', 'desc');
    $search = $request->query->get('search');

    $qb = $em->createQueryBuilder()
        ->select('d')
        ->from(DossierMedicall::class, 'd')
        ->join('d.patient', 'p');

    if ($search) {
        $qb->where('d.symptotes LIKE :search OR p.nom LIKE :search')
           ->setParameter('search', '%'.$search.'%');
    }

    $qb->orderBy('d.'.$sort, $direction);

    $dossier_medicals = $qb->getQuery()->getResult();

    return $this->render('dossier_medical/index.html.twig', [
        'dossier_medicals' => $dossier_medicals,
        'current_sort' => $sort,
        'current_direction' => $direction,
    ]);
}
    #[Route(name: 'app_back_dossier_medical_index_admin', methods: ['GET'])]
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
    
    #[Route('/pdf/{id}', name: 'app_dossier_medical_pdf')]
    public function generatePdf(): Response
{
    // 1. Création de l'instance
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // 2. Configuration de base
    $pdf->SetCreator('Votre Application');
    $pdf->SetAuthor('Votre Nom');
    $pdf->SetTitle('Mon PDF');
    $pdf->SetSubject('Exemple PDF');
    
    // 3. Ajouter une page
    $pdf->AddPage();

    // 4. Définir la police
    $pdf->SetFont('helvetica', 'B', 20);

    // 5. Ajouter le contenu
    $html = '<h1>Contenu du PDF</h1>';
    $html .= '<p>Ceci est un exemple fonctionnel</p>';
    
    $pdf->writeHTML($html, true, false, true, false, '');

    // 6. Génération du PDF
    return new Response(
        $pdf->Output('exemple.pdf', 'I'), // 'I' pour affichage direct dans le navigateur
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="exemple.pdf"'
        ]
    );
}
public function testPdf(Pdf $knpSnappyPdf): Response
{
    $html = '<h1>Test PDF</h1>';
    return new Response(
        $knpSnappyPdf->getOutputFromHtml($html),
        200,
        ['Content-Type' => 'application/pdf']
    );
}
}
