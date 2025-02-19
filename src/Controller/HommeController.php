<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HommeController extends AbstractController{
    #[Route('/homme', name: 'app_homme')]
    public function index(): Response
    {
        return $this->render('homme/index.html.twig', [
            'controller_name' => 'HommeController',
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('homme/about.html.twig', [
            'controller_name' => 'HommeController',
        ]);
    }

    #[Route('/ambulance', name: 'app_Ambulance')]
public function ambulance(): Response
{
    return $this->render('ambulance/index.html.twig', [
        'controller_name' => 'HommeController',
    ]);
}
}
