<?php

namespace CatMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentFieldsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hasDescription', 'checkbox', array(
                'label'     => 'Has Description field?',
                'required'  => true,
                'attr'     => array(
                    'checked'   => 'checked', 
                    'disabled' => 'disabled'
                )
            ))
            ->add('fieldDescriptionLabel', 'text', array(
                'label' => 'Description field label',
                'attr'     => array('placeholder'   => 'Description')
            ))
            ->add('hasTitle', 'checkbox', array(
                'label'     => 'Has Title field?',
                'required'  => true,
                //'attr'     => array('checked'   => 'checked')
            ))
            ->add('fieldTitleLabel', 'text', array(
                'label' => 'Title field label',
                'attr'     => array('placeholder'   => 'Title')
            ))                
            ->add('hasShortText', 'checkbox', array(
                'label'     => 'Has Short Text field?',
                'required'  => true,
                //'attr'     => array('checked'   => 'checked')
            ))
            ->add('fieldShortTextLabel', 'text', array(
                'label' => 'Short Text field label',
                'attr'     => array('placeholder'   => 'Short Text')
            ))
            ->add('hasFullText', 'checkbox', array(
                'label'     => 'Has Full Text field?',
                'required'  => true,
                'attr'     => array(
                    'checked'   => 'checked', 
                    'disabled' => 'disabled'
                )
            ))
            ->add('fieldFullTextLabel', 'text', array(
                'label' => 'Full Text field label',
                'attr'     => array('placeholder'   => 'Full Text')
            ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AdminBundle\Entity\ContentFields',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'content_fields';
    }
}