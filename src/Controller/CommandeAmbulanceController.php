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
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


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
// Create the Transport
// Create the Transport
$transport = Transport::fromDsn('smtp://smtp.freesmtpservers.com:25');
$mailer = new Mailer($transport);

// Create the Mailer using your created Transport
            $mailer = new Mailer($transport);


// Create a message
$email = (new Email())
    ->from('maxientrepot@gmail.com')
    ->to('amin.benhoula@esprit.tn')
    //->cc('cc@example.com')
    //->bcc('bcc@example.com')
    //->replyTo('fabien@example.com')
    //->priority(Email::PRIORITY_HIGH)
    ->subject('confirmation commande ambulance')
    ->text('Votre commande est confirmée, ambulance en route, immatricualtion : 1725TU135')
    ->html('<p>https://maps.app.goo.gl/FhLmoBojk7d7iBFt9?g_st=com.google.maps.preview.copy</p>');

$mailer->send($email);
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