<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminAjoutUserType extends AbstractType
{
     /**
 * Permet d'avoir la configuration de base d'un champs !
 * 
 * @param string $label
 * @param string $placeholder
 * @param array $options
 * @return array 
 */
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
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Saisissez un Email"))
            ->add('username', TextType::class, $this->getConfiguration("Nom d'utilisateur", "Saisissez un nom d'utilisateur"))
            ->add('password', PasswordType::class, $this->getConfiguration("Nom d'utilisateur", "Saisissez un nom d'utilisateur"));
        //     ->add('roles', EntityType::class, [
        //         'class' => Role::class,
        //         'choice_label' => 'intitule'
        //     ])   
        // ;

        //     ->add('role', EntityType::class, [
        //         'class' => Role::class,
        //         'choice_label' => 'intitule',
        //         'expanded' => true,
        //         'multiple' => true
        //     ])   
        // ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
