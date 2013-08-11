<?php

namespace CatMS\AdminBundle\Twig;


class TwigTextHelpers extends \Twig_Extension { 
    
    public function getName() {
        return 'twig_text_helpers';
    }

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('stripp', array($this, 'strippFilter'))
        );
    }

    public function getFunctions() {
        return array(
            'truncate' => new \Twig_Function_Method($this, 'truncateFunction'),
            'nl2br' => new \Twig_Function_Method($this, 'nl2brFunction'),
            'ceil' => new \Twig_Function_Method($this, 'ceilFunction'),
            'number_format' => new \Twig_Function_Method($this, 'numberFormatFunction'),
        );
    }
    
    public function strippFilter($content)
    {
        return str_replace(array('<p>', '</p>'), array('', ''), $content);
    }
    
    public function truncateFunction($str, $maxChars) {
        if (strlen($str) > ($maxChars - 3)) {
            return mb_substr($str, 0, $maxChars, 'UTF-8') . ' [...]';
        } else {
            return $str;
        }
    }

    public function nl2brFunction($string) {
        return nl2br($string);
    }
    
    public function ceilFunction($number)
    {
        return ceil($number);
    }
    
    public function numberFormatFunction($number, $round)
    {
        return number_format($number, $round);
    }
}
