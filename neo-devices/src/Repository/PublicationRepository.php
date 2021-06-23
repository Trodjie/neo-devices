<?php declare(strict_types=1);

namespace App\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class PublicationRepository extends EntityRepository
{
    public function getQueryBuilderForList(): QueryBuilder
    {
        return $this->createQueryBuilder('p')->orderBy('p.publishedAt', Criteria::DESC);
    }
}
