<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\Translator;


class UserType extends AbstractType {
   
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
                ->add('roles', ChoiceType::class, array(
                    'label' => 'Uprawnienia',
                    'multiple' => true,
                    'choices' => array(
                        'Administrator' => 'ROLE_ADMIN',
                        'Moderator' => 'ROLE_MODERATOR',
                        'Użytkownik' => 'ROLE_USER'
                    )

                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Zapisz zmianyę',
                    'attr' => array(
                        'class' => 'btn-primary'
                    )
                ) );
                
        
        parent::buildForm($builder, $options);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(array(
            'validation_groups' => array('Default', 'userManage')
        ));
    }
}