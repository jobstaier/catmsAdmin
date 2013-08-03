<?php

namespace CatMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AssetProtoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('title')  
            ->add('priority')  
            ->add('redirect')
            ->add('slug')
            ->add('imageGroup', 'entity', array(
                'class' => 'CatMSAdminBundle:ImageGroup',
                'property' => 'description',
            ));
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AdminBundle\Entity\ImageUpload',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'asset_form';
    }
}