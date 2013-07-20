<?php

namespace CatMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CatMS\AdminBundle\Form\ContentFieldsType;

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
            ->add('isRemovable', 'choice', array(
                'choices' => array('777' => 'Yes', '755' => 'Only by Developer', '000' => 'Forbidden'),
                'preferred_choices' => array('777'),
                'label' => 'Is removable?',
            ))
            ->add('contentFields', new ContentFieldsType());
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AdminBundle\Entity\ContentGroup',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'catms_adminbundle_contentgrouptype';
    }
}
