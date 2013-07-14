<?php

namespace CatMS\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * SeoRepository
 * 
 */
class ShopAddressRepository extends EntityRepository
{
    public function findPlaces($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM CatMSAdminBundle:ShopAddress p JOIN p.province pr WHERE pr.slug = :slug ORDER BY p.name DESC')
            ->setParameter('slug', $slug)
            ->getResult();
    }
    
    public function findPlacesArray($slug)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM CatMSAdminBundle:ShopAddress p JOIN p.province pr WHERE pr.slug = :slug ORDER BY p.name DESC')
            ->setParameter('slug', $slug)
            ->getScalarResult();
    }
    
    public function findPlacesFromStringArray($city)
    {
        $city = '%'.$city.'%';
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM CatMSAdminBundle:ShopAddress p JOIN p.province pr WHERE p.address LIKE :city ORDER BY p.name DESC')
            ->setParameter('city', $city)
            ->getScalarResult();   
    }
}
?>