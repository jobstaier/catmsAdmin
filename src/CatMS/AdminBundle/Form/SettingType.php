<?php

namespace CatMS\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SettingType extends AbstractType
{
    private function getFieldTypes()
    {
        return array(
            'text' => 'Text',
            'textarea' => 'Textarea',
            'checkbox' => 'Checkbox'
        );
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = ($options['form_value_field_type'] != '') ? $options['form_value_field_type'] : 'textarea';

        $builder->add('slug');
        
        $builder->add('value', (string) $type);
            
        $builder->add('description', 'textarea')
                ->add('fieldType', 'choice', array(
                    'choices' => $this->getFieldTypes(),
                    'multiple' => false,
                    'expanded' => false,
                    'label' => 'Form field type'
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CatMS\AdminBundle\Entity\Setting',
            'form_value_field_type' => 'textarea'
        ));
    }

    public function getName()
    {
        return 'catms_adminbundle_settingtype';
    }
}
