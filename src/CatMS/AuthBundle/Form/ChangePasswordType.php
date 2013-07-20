<?php

namespace CatMS\AuthBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', 'password', array('required' => true, 'label' => 'Password'))
            ->add('password_retype', 'password', array('required' => true, 'label' => 'Password retype'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AuthBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'user_password';
    }
}