<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\Translator;


class RegisterUserType extends AbstractType {
   
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add('firstName', TextType::class, array(
                    'label' => 'Imię'
                ) )
                ->add('lastName', TextType::class, array(
                    'label' => 'Nazwisko'
                ))
                ->add('username', TextType::class, array(
                    'label' => 'Nazwa użytkownika'               
                ))
                ->add('email', EmailType::class, array(
                    'label' => 'Adres email'
                ))
                ->add('register', SubmitType::class, array(
                    'label' => 'Zapisz zmiany',
                    'attr' => array(
                        'class' => 'btn-primary'
                    )
                ) );
                
        
        parent::buildForm($builder, $options);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(array(
            'validation_groups' => array('Default', 'Register')
        ));
    }
}