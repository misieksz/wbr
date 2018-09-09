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


class ArticleType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add('title', TextType::class, array(
                    'label' => 'Tytuł artykułu' 
                ))
                ->add('category', EntityType::class, array(
                    'label' => 'Kategoria',
                    'placeholder' => 'Wybierz kategorię',
                    'class' => Category::class,
                    'choice_label' => 'name'
                ))
                ->add('content', TextareaType::class, array(
                    'label' => 'Treść artykułu' 
                ))
                ->add('publishedDate', DateType::class, array(
                    'label' => 'Data publikacji',
                    'format' => 'dd-MM-yyyy',
                    'widget' => 'single_text'
                ))
                ->add('thumbnail', FileType::class, array(
                    'label' => 'Plik z obrazem'
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Zapisz artykuł',
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