<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog_index', methods: ['GET'])]
    public function index(Request $request, ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {
        // Get search parameters
        $search = $request->query->get('search');
        $authorId = $request->query->get('author');
        $categoryId = $request->query->get('category');
        $dateFrom = $request->query->get('date_from');
        $dateTo = $request->query->get('date_to');
        
        // Build query
        $queryBuilder = $articleRepository->createQueryBuilder('a')
            ->where('a.status = :status')
            ->setParameter('status', 'published')
            ->orderBy('a.createdAt', 'DESC');
        
        // Apply filters if they exist
        if ($search) {
            $queryBuilder
                ->andWhere('(a.title LIKE :search OR a.content LIKE :search)')
                ->setParameter('search', '%' . $search . '%');
        }
        
        if ($authorId) {
            $queryBuilder
                ->andWhere('a.author = :authorId')
                ->setParameter('authorId', $authorId);
        }
        
        if ($categoryId) {
            $queryBuilder
                ->innerJoin('a.categories', 'c')
                ->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }
        
        if ($dateFrom) {
            $queryBuilder
                ->andWhere('a.createdAt >= :dateFrom')
                ->setParameter('dateFrom', new \DateTime($dateFrom . ' 00:00:00'));
        }
        
        if ($dateTo) {
            $queryBuilder
                ->andWhere('a.createdAt <= :dateTo')
                ->setParameter('dateTo', new \DateTime($dateTo . ' 23:59:59'));
        }
        
        // Get results
        $articles = $queryBuilder->getQuery()->getResult();
        
        // Get all authors and categories for the search form
        $authors = $userRepository->findAll();
        
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
            'authors' => $authors,
        ]);
    }

    #[Route('/admin', name: 'app_blog_admin_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function adminIndex(Request $request, ArticleRepository $articleRepository, UserRepository $userRepository): Response
    {
        // Get search parameters
        $search = $request->query->get('search');
        $status = $request->query->get('status');
        $authorId = $request->query->get('author');
        $categoryId = $request->query->get('category');
        $dateFrom = $request->query->get('date_from');
        $dateTo = $request->query->get('date_to');
        
        // Build query
        $queryBuilder = $articleRepository->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC');
        
        // Apply filters if they exist
        if ($search) {
            $queryBuilder
                ->andWhere('(a.title LIKE :search OR a.content LIKE :search)')
                ->setParameter('search', '%' . $search . '%');
        }
        
        if ($status) {
            $queryBuilder
                ->andWhere('a.status = :status')
                ->setParameter('status', $status);
        }
        
        if ($authorId) {
            $queryBuilder
                ->andWhere('a.author = :authorId')
                ->setParameter('authorId', $authorId);
        }
        
        if ($categoryId) {
            $queryBuilder
                ->innerJoin('a.categories', 'c')
                ->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }
        
        if ($dateFrom) {
            $queryBuilder
                ->andWhere('a.createdAt >= :dateFrom')
                ->setParameter('dateFrom', new \DateTime($dateFrom . ' 00:00:00'));
        }
        
        if ($dateTo) {
            $queryBuilder
                ->andWhere('a.createdAt <= :dateTo')
                ->setParameter('dateTo', new \DateTime($dateTo . ' 23:59:59'));
        }
        
        // Get results
        $articles = $queryBuilder->getQuery()->getResult();
        
        // Get all authors and categories for the search form
        $authors = $userRepository->findAll();
        
        return $this->render('blog/admin/index.html.twig', [
            'articles' => $articles,
            'authors' => $authors,
        ]);
    }

    #[Route('/admin/new', name: 'app_blog_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $article = new Article();
        $article->setAuthor($this->getUser());
        
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
                    $this->addFlash('error', 'There was an error uploading your image.');
                }
            }

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
        if ($article->getStatus() !== 'published' && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException('The article does not exist');
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_blog_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
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
                    $this->addFlash('error', 'There was an error uploading your image.');
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
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
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