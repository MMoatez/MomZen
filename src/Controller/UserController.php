<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\RegistrationFormType;


#[Route('/user')]
final class UserController extends AbstractController{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }   

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             // Gestion de la photo uploadée
             $photoFile = $form->get('image')->getData();
             if ($photoFile) {
                 $newFilename = uniqid() . '.' . $photoFile->guessExtension();
     
                 // Déplacer le fichier vers le répertoire d'upload
                 $photoFile->move(
                     $this->getParameter('uploads_directory'), // Répertoire configuré
                     $newFilename
                 );
     
                 // Enregistrer le chemin du fichier dans l'entité User
                 $user->setImage($newFilename);
             }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
    

    #[Route('profile/{id}', name: 'app_profile', methods: ['GET'])]
    public function showprofile(User $user): Response
    {
        return $this->render('user/showprofile.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/user/forum', name: 'admin_forum')]
public function forumAdmin(ForumRepository $forumRepo): Response {
    $forums = $forumRepo->findAll();
    return $this->render('admin/forum.html.twig', [
        'forums' => $forums
    ]);
}

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $photoFile = $form->get('image')->getData();
            if ($photoFile) {
                $oldImage = $user->getImage();
                if ($oldImage) {
                    $uploadsDirectory = $this->getParameter('uploads_directory');
                    $oldImagePath = $uploadsDirectory . '/' . $oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
    
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                $photoFile->move($this->getParameter('uploads_directory'), $newFilename);
                $user->setImage($newFilename);
            }
    
            // Gestion du mot de passe
            $newPassword = $form->get('password')->getData(); // Directement depuis le formulaire
            if (!empty($newPassword)) {
                // Hacher et mettre à jour le nouveau mot de passe
          
                $user->setPassword($newPassword);
            } // Si le champ est vide, ne pas changer le mot de passe
    
            // Sauvegarder les changements
            $entityManager->flush();
    
            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}/editprofile', name: 'app_user_edit_profile', methods: ['GET', 'POST'])]
    public function editprofile(
        Request $request, 
        User $user, 
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'image
            $photoFile = $form->get('image')->getData();
            if ($photoFile) {
                $oldImage = $user->getImage();
                if ($oldImage) {
                    $uploadsDirectory = $this->getParameter('uploads_directory');
                    $oldImagePath = $uploadsDirectory . '/' . $oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
    
                $newFilename = uniqid() . '.' . $photoFile->guessExtension();
                $photoFile->move($this->getParameter('uploads_directory'), $newFilename);
                $user->setImage($newFilename);
            }
    
            // Gestion du mot de passe
            $newPassword = $form->get('password')->getData(); // Directement depuis le formulaire
            if (!empty($newPassword)) {
                // Hacher et mettre à jour le nouveau mot de passe
          
                $user->setPassword($newPassword);
            } // Si le champ est vide, ne pas changer le mot de passe
    
            // Sauvegarder les changements
            $entityManager->flush();
    
            return $this->redirectToRoute('app_profile', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('user/editprofile.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    private $entityManager;

    // Inject the Doctrine service into the controller
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

   
}
