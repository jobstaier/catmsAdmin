<?php

namespace CatMS\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * ImageGroupRepository
 * 
 */
class ImageGroupRepository extends EntityRepository
{
    public function fetchGroupImages($slug)
    {
        return $this->getManager()
                ->createQuery('SELECT i FROM CatMSAdminBundle:ImageUpload i JOIN i.imageGroup ig WHERE ig.slug = :slug ORDER BY i.priority ASC')
                ->setParameter('slug', $slug)
                ->getResult();
    }
}
?>