<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ProductRepository extends EntityRepository
{
    public function getQueryBuilderForSearch(string $searchQuery): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where('p.title LIKE :search_query')
            ->setParameter('search_query', "%$searchQuery%")
            ->orderBy('p.title');

        return $qb;
    }

    public function getQueryBuilderForCategory(Category $category): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where('p.category = :category')
            ->setParameter('category', $category);

        return $qb;
    }
}
