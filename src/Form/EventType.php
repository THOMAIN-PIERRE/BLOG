<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
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
        // $builder
        // ->add('name', TextType::class, $this->getConfiguration("Titre de l'évènement", "Saisissez le nom de votre évènement"))
        // ->add('subtitle', TextType::class, $this->getConfiguration("Sous-Titre de l'évènement", "Saisissez le sous-titre de votre évènement"))
        // ->add('date', DateTimeType::class, $this->getConfiguration("Date de l'évènement", "Saisissez une date pour votre évènement"))
        // ->add('place', TextType::class, $this->getConfiguration("Lieu", "Saisissez le lieu de votre évènement"))
        // ->add('picture', UrlType::class, $this->getConfiguration("Photo d'illustration", "Saisissez l'URL d'une photo illustrant votre évènement"))
        // ->add('description', TextareaType::class,  [
        //         'label' => 'Texte de l\'article',
        //         'attr' => [
        //         'label' => 'Description de l\évènement',
        //         'rows' => '10',
        //         'cols' =>  '20'
        //         ] 
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
