<?php

namespace CatMS\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * ContentGroupRepository
 * 
 */
class ContentGroupRepository extends EntityRepository
{
    public function fetchGroupContent($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT c FROM CatMSAdminBundle:ContentManager c JOIN c.contentGroup cg WHERE cg.slug = :slug ORDER BY c.priority ASC')
            ->setParameter('slug', $slug)
            ->getResult();
    }
}
?>