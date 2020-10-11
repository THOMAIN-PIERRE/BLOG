<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CustomizerType extends AbstractType
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
                'BLEU (Thème par défaut)' => 'bg-primary',
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
                'BLEU (Thème par défaut)' => 'bg-primary',
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
                'BLEU' => 'alert alert-primary',
                'VERT' => 'alert alert-success',
                'ROUGE' => 'alert alert-danger',
                'ORANGE' => 'alert alert-warning',
                'BLEU FONCE' => 'alert alert-info',
                'GRIS' => 'alert alert-secondary',
            ],
                'multiple'  => false,   
                'expanded'  => false, 
                'label' => 'Couleur de fond de l\'écran',
                // 'mapped' => true 
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
