<?php

namespace CatMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug')
            ->add('description', 'textarea')
            ->add('imageWidth', 'text', array('label' => 'Image width [px]'))
            ->add('imageHeight', 'text', array('label' => 'Image height [px]'))
            ->add('hasThumbnails', 'checkbox', array(
                'label' => 'Set thumbnails?'
            ))
            ->add('thumbnailWidth', 'integer', array(
                'label' => 'Thumbnail width [px]'
            ))
            ->add('thumbnailHeight', 'integer', array(
                'label' => 'Thumbnail height [px]'
            ))  
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AdminBundle\Entity\ImageGroup'
        ));
    }

    public function getName()
    {
        return 'catms_adminbundle_imagegrouptype';
    }
}
