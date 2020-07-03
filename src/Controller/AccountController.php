<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\PasswordUpdate;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{

     /**
     * Permet d'afficher les informations personnelles de l'utilisateur connecté
     * 
     * @Route("/account", name="account_index")
     * IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount() {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }


    /**
     * Permet d'afficher le formulaire de modification de profil
     * 
     * @Route("/account/profile", name="account_profile")
     * IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager) {

        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'sucess',
                "Votre profil a été mis à jour !"
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

     /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/update-password", name="account_password")
     * IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {

        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez saisi est erroné !"));
            }else{
               $newPassword = $passwordUpdate->getNewPassword();
               $hash = $encoder->encodePassword($user, $newPassword);

               $user->setPassword($hash);
               
               $manager->persist($user);
               $manager->flush();
               
               $this->addFlash(
                   'success',
                   "Les modifications ont bien été enregistrées"
               );

               return $this->redirectToRoute('home');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    
    }

    /**
     * Permet d'afficher la liste des articles écrits par un utilisateur
     * 
     * @Route("/account/publications", name="account_publications")
     * IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function publications() {

        $repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();

        return $this->render('account/publications.html.twig', [
            'articles' => $articles
        ]);

    }

    /**
     * Permet d'afficher la liste des commentaires écrits par un utilisateur
     * 
     * @Route("/account/commentaires", name="account_commentaires")
     * IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function commentaires() {

        $repo = $this->getDoctrine()->getRepository(Comment::class);

        $commentaires = $repo->findAll();

        return $this->render('account/commentaires.html.twig', [
            'commentaires' => $commentaires
        ]);

    }

}
