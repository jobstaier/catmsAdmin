<?php

namespace CatMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug')
            ->add('description', 'textarea')
            ->add('relatedImages', 'entity', array(
                'class' => 'CatMSAdminBundle:ImageGroup',
                'property' => 'description',
                'label' => 'Related image groups',
                'required' => false,
                'multiple' => true
            ))    
            ->add('manual', 'textarea')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AdminBundle\Entity\ContentGroup'
        ));
    }

    public function getName()
    {
        return 'catms_adminbundle_contentgrouptype';
    }
}
