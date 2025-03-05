<?php

namespace App\Service;

use App\Entity\Reclamation;
use App\Entity\Response;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ReclamationMailer
{
    private MailerInterface $mailer;
    private string $senderEmail;
    private string $senderName;

    public function __construct(
        MailerInterface $mailer,
        string $senderEmail = 'moatezmathlouthitr@gmail.com',
        string $senderName = 'MomZen Support'
    ) {
        $this->mailer = $mailer;
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
    }

    /**
     * Send an email notification to the user when a response is added to their reclamation
     */
    public function sendResponseNotification(Response $response): void
    {
        $reclamation = $response->getReclamation();
        $user = $reclamation->getUser();
        
        if (!$user || !$user->getEmail()) {
            return;
        }
        
        $email = (new TemplatedEmail())
            ->from(new Address($this->senderEmail, $this->senderName))
            ->to(new Address($user->getEmail(), $user->getPrenom() . ' ' . $user->getNom()))
          
           
           ->subject('New Response to Your Reclamation: ' . $reclamation->getTitle())
            ->htmlTemplate('emails/reclamation_response.html.twig')
            ->context([
                'user' => $user,
                'reclamation' => $reclamation,
                'response' => $response
            ]);
            
        $this->mailer->send($email);
    }
} 