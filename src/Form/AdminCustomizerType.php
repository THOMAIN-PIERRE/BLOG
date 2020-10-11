<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdminCustomizerType extends AbstractType
{

    private function getConfiguration($label, $placeholder, $options = []) {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
                ]
            ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('navColor', ChoiceType::class,[
                'choices' => [
                'SOMBRE (Thème par défaut)' => 'bg-dark', 
                'BLEU' => 'bg-primary',
                'VERT' => 'bg-success',
                'ROUGE' => 'bg-danger',
                'ORANGE' => 'bg-warning',
                'BLEU FONCE' => 'bg-info',
                'GRIS FONCE' => 'bg-secondary',
            ],
                'multiple'  => false,   
                'expanded'  => false, 
                'label' => 'Couleur du menu de navigation',
                // 'mapped' => true 
            ])



            ->add('tableColor', ChoiceType::class,[
                'choices' => [
                'SOMBRE (Thème par défaut)' => 'bg-dark', 
                'BLEU' => 'bg-primary',
                'VERT' => 'bg-success',
                'ROUGE' => 'bg-danger',
                'ORANGE' => 'bg-warning',
                'BLEU FONCE' => 'bg-info',
                'GRIS FONCE' => 'bg-secondary',
            ],
                'multiple'  => false,   
                'expanded'  => false, 
                'label' => 'Couleur de l\'entete des tableaux ',
                // 'mapped' => true 
            ])



            ->add('bgColor', ChoiceType::class,[
                'choices' => [
                'BLANC (Thème par défaut)' => 'bg-white', 
                'BLEU' => 'bg-primary',
                'VERT' => 'bg-success',
                'ROUGE' => 'bg-danger',
                'ORANGE' => 'bg-warning',
                'BLEU FONCE' => 'bg-info',
                'GRIS' => 'bg-secondary',
            ],
                'multiple'  => false,   
                'expanded'  => false, 
                'label' => 'Couleur de fond de l\'écran',
                // 'mapped' => true 
            ])



            ->add('nbArticlePerPage', IntegerType::class, $this->getConfiguration("Nombre d'article à afficher par page", "Indiquez un chiffre entre 1 et 50", [
                'attr' => [
                    'min' => 1,
                    'max' => 50,
                    'step' => 1
                ]
            ]))


            
            ->add('nbCommentPerPage', IntegerType::class, $this->getConfiguration("Nombre d'article à afficher par page", "Indiquez un chiffre entre 1 et 50", [
                'attr' => [
                    'min' => 1,
                    'max' => 50,
                    'step' => 1
                ]
            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}

    
            
    
   
