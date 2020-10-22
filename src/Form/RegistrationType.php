<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
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
            ->add('email')
            ->add('username')
            ->add('password', PasswordType::class)
            // ->add('password', PasswordType::class, [
            //     // instead of being set onto the object directly,
            //     // this is read and encoded in the controller
            //     'mapped' => false,
            //     'constraints' => [
            //        new NotBlank([
            //           'message' => 'Le mot de passe ne peut être vide',
            //        ]),
            //        new Length([
            //           'min' => 8,
            //           'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
            //           // max length allowed by Symfony for security reasons
            //           'max' => 4096,
            //        ]),
            //        new Regex([
            //           'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/i',
            //           'htmlPattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$',
            //           'message' => 'Votre mot de passe doit comporter au moins une minuscule, une majuscule, un chiffre et un caractère spécial ',
            //        ]),
            //     ],
            //  ])
            ->add('confirm_password', PasswordType::class)
            ->add('avatar', UrlType::class)
            ->add('rgpd', CheckboxType::class, [
                'label' => 'J\'accepte que mes informations personnelles et mes commentaires soient stockés dans la base de données de .BLOG. J\'ai bien noté qu\'en aucun cas, ces données ne seront cédées à des tiers.',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}