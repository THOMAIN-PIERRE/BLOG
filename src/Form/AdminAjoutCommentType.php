<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminAjoutCommentType extends AbstractType
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
        
            ->add('article', EntityType::class, [
                'label' => 'Titre de l\'article',
                'class' => Article::class,
                'choice_label' => 'title',
                ]) 
            ->add('content', TextareaType::class, $this->getConfiguration("Commentaire", "Modifier le commentaire"))
            ->add('rating', IntegerType::class, $this->getConfiguration("Evaluation de l'article", "Attribuez une note entre 0 et 5", [
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 1
                ]
            ]))
            ->add('status', ChoiceType::class, [
                'choices' => [
                'Validé (le commentaire sera publié)' => 'Validé',
                'Non validé (le commentaire ne sera pas publié)' => 'Non validé',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Statut'
            ])

            // ->add('status', TextareaType::class, $this->getConfiguration("Statut de publication", "Indiquer \"Validé\" pour publier ou \"Non validé\" pour empêcher la publication"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
