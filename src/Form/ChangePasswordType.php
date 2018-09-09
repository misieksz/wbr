<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePasswordType extends AbstractType 
{
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add('currentpassword', PasswordType::class, array(
                    'label' => 'Aktualne hasło',
                    'mapped' => false,
                    'constraints' => array(
                        new SecurityAssert\UserPassword(array(
                            'message' => 'Podaj aktualne hasło!'
                        ))
                    )
                ))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array(
                        'label' => 'Nowe hasło'
                    ),
                    'second_options' => array(
                        'label' => 'Powtórz nowe hasło'
                    )
                ))
                ->add('send', SubmitType::class, array(
                    'label' => 'Zmień hasło',
                    'attr' => array(
                        'class' => 'btn btn-success'
                    )
                ));
        
        parent::buildForm($builder, $options);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(array(
            'validation_groups' => array('Default', 'ChangePassword')
        ));
    }
}
