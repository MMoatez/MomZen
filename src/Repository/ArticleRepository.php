<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Find all published articles ordered by creation date
     *
     * @return Article[]
     */
    public function findPublishedArticles(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->setParameter('status', 'published')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find latest published articles
     *
     * @param int $limit Number of articles to return
     * @return Article[]
     */
    public function findLatestPublished(int $limit = 5): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->setParameter('status', 'published')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search articles by title or content
     *
     * @param string $query Search term
     * @param bool $publishedOnly Only include published articles
     * @return Article[]
     */
    public function searchArticles(string $query, bool $publishedOnly = true): array
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.title LIKE :query')
            ->orWhere('a.content LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('a.createdAt', 'DESC');

        if ($publishedOnly) {
            $qb->andWhere('a.status = :status')
               ->setParameter('status', 'published');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find articles by author
     *
     * @param int $authorId
     * @param bool $publishedOnly Only include published articles
     * @return Article[]
     */
    public function findByAuthor(int $authorId, bool $publishedOnly = false): array
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->orderBy('a.createdAt', 'DESC');

        if ($publishedOnly) {
            $qb->andWhere('a.status = :status')
               ->setParameter('status', 'published');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Count total articles by status
     *
     * @return array Array with status as key and count as value
     */
    public function countByStatus(): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.status, COUNT(a) as count')
            ->groupBy('a.status')
            ->getQuery()
            ->getResult();
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
} 