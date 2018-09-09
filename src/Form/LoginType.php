<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\Translator;


class LoginType extends AbstractType {
   
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add('username', TextType::class, array(
                    'label' => 'Nazwa użytkownika'               
                ))
                ->add('password', PasswordType::class, array(
                    'label' => 'Hasło' 
                ))
                ->add('remember_me', CheckboxType::class, array(
                    'label' => 'Zapamiętaj mnie'
                ))
                ->add('login', SubmitType::class, array(
                    'label' => 'Zaloguj się',
                    'attr' => array(
                        'class' => 'btn-primary'
                    )
                ) );
                
        
        parent::buildForm($builder, $options);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
    }
}