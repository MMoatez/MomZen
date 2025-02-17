<?php

namespace App\Controller;
use App\Form\CommentaireType;
use App\Entity\Commentaire; 
use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface; // Ajoutez cette ligne

#[Route('/forum')]
final class ForumController extends AbstractController
{
    #[Route('/admin/forum', name: 'app_forum_backindex')]
public function backIndex(ForumRepository $forumRepository): Response
{
    return $this->render('forum/backindex.html.twig', [
        'forums' => $forumRepository->findAll(),
    ]);
}
    #[Route(name: 'app_forum_index', methods: ['GET'])]
    public function index(ForumRepository $forumRepository): Response
    {
        return $this->render('forum/index.html.twig', [
            'forums' => $forumRepository->findAll(),
        ]);
    }
    
    #[Route(name: 'app_backforum_index', methods: ['GET'])]
    public function index_back(ForumRepository $forumRepository): Response
    {
        return $this->render('forum/backindex.html.twig', [
            'forums' => $forumRepository->findAll(),
        ]);
    }
    
    #[Route('/new', name: 'app_forum_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de l'upload d'image
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'), // Chemin défini dans services.yaml
                        $newFilename
                    );
                    $forum->setImage($newFilename);
                } catch (FileException $e) {
                    // Gérer l'erreur d'upload
                }
            }

            $forum->setDatePublication(new \DateTime()); // Ajout automatique de la date
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_index');
        }

        return $this->render('forum/new.html.twig', [
            'forum' => $forum,
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}', name: 'app_forum_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $commentaire->setForum($forum);
        $commentaire->setDatePublication(new \DateTime());
    
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_forum_show', ['id' => $forum->getId()]);
        }
    
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
            'commentaire_form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_forum_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/edit.html.twig', [
            'forum' => $forum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forum_delete', methods: ['POST'])]
    public function delete(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
    }
   

}