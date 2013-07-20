<?php

namespace CatMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug', 'text', array('label' => 'Slug'))
            ->add('description', 'textarea')
            ->add('title', 'text')
            ->add('shortText', 'textarea',  array('label' => 'Short Text'))
            ->add('fullText', 'textarea',  array('label' => 'Full text'))
            ->add('contentGroup', 'entity', array(
                'class' => 'CatMSAdminBundle:ContentGroup',
                'property' => 'description',
                'label' => 'Content group'
            ))
            ->add('priority')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AdminBundle\Entity\ContentManager'
        ));
    }

    public function getName()
    {
        return 'catms_adminbundle_contentmanagertype';
    }
}
