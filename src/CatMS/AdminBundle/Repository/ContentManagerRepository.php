<?php

namespace CatMS\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * ContentManagerRepository
 * 
 */
class ContentManagerRepository extends EntityRepository
{
    public function findTabs($slug)
    {
        $results = $this->createQueryBuilder('c')
                ->join('c.contentGroup', 'cg')
                ->where('cg.slug = :slug')
                ->orderBy('c.priority', 'ASC')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getResult();
        
        return $results;
    }
}
?>