<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('author', TextType::class, $this->getConfiguration("Nom d'utilisateur", "Saisissez votre pseudo")) 
            ->add('content', TextareaType::class, $this->getConfiguration("Commentaire", "Saisissez votre commentaire")) 
            ->add('rating', IntegerType::class, $this->getConfiguration("Evaluation de l'article", "Attribuez une note entre 0 et 5", [
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 1
                ]
            ])) 
            //->add('utilisateur', IntegerType::class, $this->getConfiguration("Propriétaire de l'article", "Saisissez le numéro d'identifiant de l'utilisateur auquel appartient l'article")) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
