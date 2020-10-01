<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Quote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Quote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quote[]    findAll()
 * @method Quote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quote::class);
    }

    public function random(Category $category = null): ?Quote
    {
        $queryBuilder = $this->createQueryBuilder('q')
            ->select('q.id');

        if (null != ($category)) {
            $queryBuilder = $this->createQueryBuilder('q')
                ->select('q.id')
                ->setParameter('val', $category)
                ->andWhere('q.category = :val')
            ;
        }

        $ids = $queryBuilder->getQuery()->getScalarResult();
        if (empty($ids)) {
            return null;
        }

        $ids = array_column($ids, 'id');

        return $this->find($ids[array_rand($ids)]);
    }

    public function lastQuotes($Author)
    {
        $queryBuilder = $this->createQueryBuilder('q')
            ->where('q.Author = :auteur')
            ->setParameter('auteur', $Author)
            ->orderBy('q.createdAt', 'DESC')
            ->setMaxResults(5);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
