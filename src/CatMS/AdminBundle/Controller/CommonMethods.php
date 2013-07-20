<?php

namespace CatMS\AdminBundle\Controller;

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
    
    /*
    public static function getPageTitle($description)
    {
        $pattern = "(#pageTitle=([a-zA-Z0-9 ])+#)";
        preg_match($pattern, $description, $matches);
        if (count($matches) > 0) {
            return str_replace(array('#pageTitle=', '#'), array('', ''), $matches[0]);
        } else {
            return null;
        }
    }
    
    public static function getMetaDescription($description)
    {
        $pattern = "(#metaDescription=([a-zA-Z0-9 ])+#)";
        preg_match($pattern, $description, $matches);
        if (count($matches) > 0) {
            return str_replace(array('#pageTitle=', '#'), array('', ''), $matches[0]);
        } else {
            return null;
        }
    }
    
    public static function getMetaKeywords($description)
    {
        $pattern = "(#metaKeywords=([a-zA-Z0-9 ])+#)";
        preg_match($pattern, $description, $matches);
        if (count($matches) > 0) {
            return str_replace(array('#pageTitle=', '#'), array('', ''), $matches[0]);
        } else {
            return null;
        }
    }
    */
}

?>
