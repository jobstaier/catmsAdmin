<?php

namespace CatMS\AdminBundle\Utility;

class CommonMethods
{
    /**
     * Cast records per page to integer even if setting value is not defined.
     * 
     * @param integer $recordsPerPage
     * @param \Symfony\Component\DependencyInjection\Container $container
     * @return integer
     */
    public static function castRecordsPerPage($recordsPerPage, \Symfony\Component\DependencyInjection\Container $container)
    {
        if (null === $recordsPerPage || $recordsPerPage->getValue() == 0) {
            return $container->getParameter('knp_paginator.page_range');
        } else {
            return (int)$recordsPerPage->getValue();
        }
    } 
}

?>
