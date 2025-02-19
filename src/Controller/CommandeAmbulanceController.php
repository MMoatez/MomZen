<?php
// src/Controller/CommandeAmbulanceController.php
namespace App\Controller;

use App\Entity\Voyage;
use App\Form\VoyageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeAmbulanceController extends AbstractController
{
    #[Route('/commander-ambulance', name: 'app_commander_ambulance')]
    public function commander(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le voyage en base de données
            $entityManager->persist($voyage);
            $entityManager->flush();

            // Rediriger vers une page de confirmation
            return $this->redirectToRoute('app_commande_confirmation');
        }

        return $this->render('commande_ambulance/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/commande-confirmation', name: 'app_commande_confirmation')]
    public function confirmation(): Response
    {
        return $this->render('commande_ambulance/confirmation.html.twig');
    }
}