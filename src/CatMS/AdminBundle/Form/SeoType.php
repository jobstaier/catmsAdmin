<?php

namespace CatMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug')
            ->add('pageTitle', 'text', array('label' => 'Page title', 'required' => false))
            ->add('metaDescription', 'textarea', array('label' => 'Meta description', 'required' => false))
            ->add('metaKeywords', 'textarea', array('label' => 'Meta keywords', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AdminBundle\Entity\Seo'
        ));
    }

    public function getName()
    {
        return 'catms_adminbundle_seotype';
    }
}
