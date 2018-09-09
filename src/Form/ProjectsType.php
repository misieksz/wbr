<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Translation\Translator;

class ProjectsType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add('title', TextType::class, array(
                    'label' => 'Tytuł projektu'
                ))
                ->add('content', TextareaType::class, array(
                    'label' => 'Opis projektu'
                ))
                ->add('files', FileType::class, array(
                    'label' => 'Galeria zdjęć',
                    'multiple' => true,
                    'attr' => array(
                        'multiple' => 'multiple'
                    )
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Utwórz projekt',
                    'attr' => array(
                        'class' => 'btn btn-success'
                    )
                ) );
        
        parent::buildForm($builder, $options);
    }
    
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(array(
            'validation_groups' => array('Default', 'Projects')
            ));
    }
}