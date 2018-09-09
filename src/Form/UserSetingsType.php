<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;


class UserSetingsType extends AbstractType 
{
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add('username', TextType::class, array(
                    'label' => 'Nazwa użytkownika',
                ))
                ->add('send', SubmitType::class, array(
                    'label' => 'Zmień nazwę',
                    'attr' => array(
                        'class' => 'btn btn-success'
                    )
                ));
        
        parent::buildForm($builder, $options);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(array(
            'validation_groups' => array('Default', 'ChangeDetails')
        ));
    }
}
