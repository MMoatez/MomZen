<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Entity\User;

use App\Form\RendezvousType;
use App\Repository\RendezvousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Dossiermedical;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/rendezvous')]
final class RendezvousController extends AbstractController
{
    #[Route(name: 'app_rendezvous_index', methods: ['GET'])]
    public function index(RendezvousRepository $rendezvousRepository): Response
    {
        return $this->render('rendezvous/index.html.twig', [
            'rendezvouses' => $rendezvousRepository->findAll(),
        ]);
    }

    
    #[Route('/new', name: 'app_rendezvous_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')] // Assure que l'utilisateur est connecté
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $rendezvou = new Rendezvous();
        
        // Récupérer l'utilisateur connecté et l'affecter comme patient
        $user = $security->getUser();
        $rendezvou->setIdpatient($user);
    
        $form = $this->createForm(RendezvousType::class, $rendezvou);
       
        $form->handleRequest($request);
        
    
        if ($form->isSubmitted() && $form->isValid()) {
            // ✅ Extra check to ensure the date is not null
            if ($rendezvou->getDate() === null) {
                $this->addFlash('error', 'Veuillez sélectionner une date.');
                return $this->render('rendezvous/new.html.twig', [
                    'rendezvou' => $rendezvou,
                    'form' => $form,
                ]);
            }
        
            $entityManager->persist($rendezvou);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_rendezvous_show_front', ['userId' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('rendezvous/new.html.twig', [
            'rendezvou' => $rendezvou,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_rendezvous_show', methods: ['GET'])]
    public function show(Rendezvous $rendezvou): Response
    {
        return $this->render('rendezvous/show.html.twig', [
            'rendezvou' => $rendezvou,
        ]);
    }

    #[Route('/mesrendezvous/{userId}', name: 'app_rendezvous_show_front', methods: ['GET', 'POST'])]
    public function mesrendezvous($userId, EntityManagerInterface $entityManager, Security $security): Response
    {
        $currentUser = $security->getUser();
        $roles = $currentUser->getRoles();
    
        // Redirect doctors to their specific rendezvous route
        if (in_array('ROLE_DOCTEUR', $roles)) {
            return $this->redirectToRoute('app_rendezvous_docteur_front', ['userId' => $currentUser->getId()]);
        }
    
        // Fetch rendezvous based on user ID
        $rendezvousRepository = $entityManager->getRepository(Rendezvous::class);
        $rendezvous = $rendezvousRepository->createQueryBuilder('r')
            ->where('r.idpatient = :user')
            ->setParameter('user', (int) $userId)
            ->getQuery()
            ->getResult();
    
        // Regular users see only their own rendezvous
        return $this->render('rendezvous/frontrendezvous.html.twig', [
            'rendezvous' => $rendezvous,
            'noRendezvous' => empty($rendezvous),
            'userId' => $userId,
        ]);
    }
    

    







/*


    #[Route('/mesrendezvous/{userId}', name: 'app_rendezvous_show_front', methods: ['GET', 'POST'])]
    public function mesrendezvous($userId, EntityManagerInterface $entityManager): Response
    {

        $rendezvou = new Rendezvous();

        // Fetch rendezvous for the given userId
        $rendezvousRepository = $entityManager->getRepository(Rendezvous::class);
        $rendezvous = $rendezvousRepository->createQueryBuilder('r')
            ->where('r.idpatient = :user')
            ->setParameter('user', $userId) // Use userId from the URL to filter
            ->getQuery()
            ->getResult();
    

        // If no rendezvous found, you can return a message or redirect
        if (empty($rendezvous)) {
            $form = $this->createForm(RendezvousType::class, $rendezvou);

            return $this->render('rendezvous/new.html.twig', [
                'form' => $form,
                'rendezvous' => $rendezvou,

            ]);      
          }
    

        return $this->render('rendezvous/frontrendezvous.html.twig', [
            
            'rendezvous' => $rendezvous,
        ]);

       
    }

*/

#[Route('/mesrendezvous/doctor/{userId}', name: 'app_rendezvous_docteur_front', methods: ['GET'])]
public function mesRendezvousDocteur($userId, EntityManagerInterface $entityManager): Response
{
    // Vérifier que l'utilisateur existe
    $userRepository = $entityManager->getRepository(User::class);
    $user = $userRepository->find($userId);

    if (!$user || !in_array('ROLE_DOCTEUR', $user->getRoles())) {
        // Si l'utilisateur n'est pas un docteur, on crée une exception d'accès
        throw $this->createAccessDeniedException("Vous devez être un docteur pour voir les rendez-vous.");
    }

    // Récupérer les rendez-vous associés au docteur
    $rendezvousRepository = $entityManager->getRepository(Rendezvous::class);
    $rendezvous = $rendezvousRepository->createQueryBuilder('r')
        ->where('r.idmedecin = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();

    return $this->render('rendezvous/docteurrendezvous.html.twig', [
        'rendezvous' => $rendezvous,
    ]);
}

    


#[Route('/{id}/edit', name: 'app_rendezvous_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Rendezvous $rendezvou, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(RendezvousType::class, $rendezvou);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_rendezvous_show_front', [
            'userId' => $rendezvou->getIdpatient()->getId() // Récupérer l'ID du patient
        ], Response::HTTP_SEE_OTHER);
    }

    return $this->render('rendezvous/edit.html.twig', [
        'rendezvou' => $rendezvou,
        'form' => $form,
    ]);
}


#[Route('/{id}', name: 'app_rendezvous_delete', methods: ['POST'])]
public function delete(Request $request, Rendezvous $rendezvou, EntityManagerInterface $entityManager): Response
{
    $userId = $rendezvou->getIdpatient()->getId(); // Récupérer l'ID du patient

    if ($this->isCsrfTokenValid('delete'.$rendezvou->getId(), $request->getPayload()->getString('_token'))) {
        $entityManager->remove($rendezvou);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_rendezvous_show_front', [
        'userId' => $userId // Passer l'ID du patient dans la redirection
    ], Response::HTTP_SEE_OTHER);
}


    #[Route('/rendezvous/{id}/realiser', name: 'rendezvous_realiser')]
public function realiser(Rendezvous $rendezvous, EntityManagerInterface $entityManager): Response
{
    // Marquer le rendez-vous comme réalisé
    $rendezvous->setRealise(true);
    $entityManager->flush();


    $idpatient = $rendezvous->getIdpatient();
    $dossierMedical = $entityManager->getRepository(Dossiermedical::class)->findOneBy(['idpatient' => $idpatient]);



if ($dossierMedical) {
        // Rediriger vers la page d'édition du dossier médical existant
        return $this->redirectToRoute('app_dossiermedical_edit', ['id' => $dossierMedical->getId()]);
    } else {
        // Rediriger vers la création d'un nouveau dossier médical
        return $this->redirectToRoute('app_dossiermedical_new', [
            'idpatient_id' => $idpatient->getId() // Passer l'ID du idpatient pour pré-remplir le formulaire
        ]);
    }

    /* Rediriger vers le formulaire de saisie du dossier médical
    return $this->redirectToRoute('dossiermedical_edit', ['id' => $rendezvous->getDossierMedical()->getId()]);
*/
}

}
