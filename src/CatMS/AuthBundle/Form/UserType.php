<?php

namespace CatMS\AuthBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    private function getRoles()
    {
        return array(
            'ROLE_ADMIN' => 'Admin',
            'ROLE_DEVELOPER' => 'Developer',
            'ROLE_USER' => 'User'
        );
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('attr' => array(
                    'placeholder' => 'Username'
                )
            ))
            ->add('email', 'email', array('attr' => array(
                    'placeholder' => 'Email'
                )
            ))
            ->add('roles', 'choice', array(
                'choices' => $this->getRoles(),
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('isActive', 'checkbox', array('label' => 'Is Active?', 'attr' => array('class' => 'checkbox_inline'), 'required' => false))
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
        return 'user';
    }
}
