<?php

namespace App\Form;

use App\Entity\Category;
use App\Data\SearchData;;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SearchType extends AbstractType
{

    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher un article'
                ]
            ])

            // On récupère toutes les catégories disponibles en BDD
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                
            ])

            // ->add('min', NumberType::class, [
            //     'label' => false,
            //     'required' => false,
            //     'attr' => [
            //         'placeholder' => 'Min'
            //     ]
            // ])

            // ->add('max', NumberType::class, [
            //     'label' => false,
            //     'required' => false,
            //     'attr' => [
            //         'placeholder' => 'Max'
            //     ]
            // ])

            // ->add('promo', CheckboxType::class, [
            //     'label' => 'En promotion',
            //     'required' => false,
            // ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
            $resolver->setDefaults([
                'data_class' => SearchData::class,
                'method' => 'GET',
                'csrf_protection' => false
            ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

}