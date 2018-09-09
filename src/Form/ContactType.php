<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\Translator;

class ContactType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add('name', TextType::class, array(
                    'label' => 'Imię i Nazwisko' 
                ))
                ->add('email', EmailType::class, array(
                    'label' => 'Adres e-mail' 
                ))
                ->add('subject', TextType::class, array(
                    'label' => 'Temat wiadomości'
                ))
                ->add('message', TextareaType::class, array(
                    'label' => 'Wpisz wiadomość',
                    'attr' => array(
                        'rows' => 4
                    )
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Wyślij wiadomość',
                    'attr' => array(
                        'class' => 'btn btn-success'
                    )
                ) );
        
        parent::buildForm($builder, $options);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
    }
}