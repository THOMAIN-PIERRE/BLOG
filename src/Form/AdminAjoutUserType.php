<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\IsTrue;

class AdminAjoutUserType extends AbstractType
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
            ->add('username', TextType::class, $this->getConfiguration("Nom d'utilisateur", "Saisissez un nom d'utilisateur"))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Saisissez votre Email"))
            // ->add('password', PasswordType::class, $this->getConfiguration("Mot de passe", "Saisissez un  mot de passe"))
            // ->add('confirm_password', PasswordType::class, $this->getConfiguration("Confirmation de votre mot de passe", "Connfirmez votre mot de passe"))
            ->add('avatar', UrlType::class, $this->getConfiguration("Avatar", "Saisissez l'URL de votre avatar"))
            // ->add('Roles', ChoiceType::class,[
            //     'choices' => [
            //     'Administrateur' => '1', 
            //     'Editeur' => '8',
            //     'Utilisateur' => '9'
            // ],
            //     'multiple'  => true,   
            //     'expanded'  => true, 
            //     'label' => 'Rôle(s)',
            //     // 'mapped' => true 
            // ])
            ->add('userRoles', EntityType::class,[
                'label' => 'Rôle(s)',
                'class' => Role::class,
                'choice_label' => 'intitule',
                // 'choice_value' => 'intitule',
                'multiple'  => true,   
                'expanded'  => true, 
                'mapped' => true
            ])
            // ->add('rgpd', CheckboxType::class, [
            //     'label' => 'J\'accepte que mes informations personnelles et mes commentaires soient stockés dans la base de données de .BLOG. J\'ai bien noté qu\'en aucun cas, ces données ne seront cédées à des tiers.',
            //     'required' => true

            // ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}