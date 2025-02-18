<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'articles' => $articleRepository->findBy(['status' => 'published'], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/admin', name: 'app_blog_admin_index', methods: ['GET'])]
    public function adminIndex(ArticleRepository $articleRepository): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('blog/admin/index.html.twig', [
            'articles' => $articleRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/admin/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $article = new Article();
        
        // Create a temporary author user
        $author = new User();
        $author->setEmail("author@example.com");
        $author->setNom("Author");
        $author->setPrenom("User");
        $author->setPassword("author123");
        $author->setNumTel(12345678);
        $author->setGenre("MALE");
        $author->setImage("author.jpg");
        
        $article->setAuthor($author);
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('blog_images_directory'),
                        $newFilename
                    );
                    $article->setImage($newFilename);
                } catch (FileException $e) {
                    // Handle exception
                }
            }

            $entityManager->persist($author);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_admin_index');
        }

        return $this->render('blog/admin/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_blog_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        //if ($article->getStatus() !== 'published' && !$this->isGranted('ROLE_ADMIN')) {
        //    throw $this->createNotFoundException('The article does not exist');
        //}

        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('blog_images_directory'),
                        $newFilename
                    );
                    
                    // Delete old image if exists
                    if ($article->getImage()) {
                        $oldImagePath = $this->getParameter('blog_images_directory').'/'.$article->getImage();
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    
                    $article->setImage($newFilename);
                } catch (FileException $e) {
                    // Handle exception
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_blog_admin_index');
        }

        return $this->render('blog/admin/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_blog_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            // Delete image if exists
            if ($article->getImage()) {
                $imagePath = $this->getParameter('blog_images_directory').'/'.$article->getImage();
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_admin_index');
    }
} 