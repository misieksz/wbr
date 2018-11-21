<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RememberPasswordType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add('email', EmailType::class, array(
                    'label' => 'Podaj adres e-mail'
                    ))
                ->add('send', SubmitType::class, array(
                    'label' => 'Przypomnij hasło',
                    'attr' => array(
                        'class' => 'btn btn-success'
                    )
                ));
        
        parent::buildForm($builder, $options);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(array(
            'validation_groups' => array('RememberPassword')
        ));
    }
}
