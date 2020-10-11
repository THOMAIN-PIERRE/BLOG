<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire d'inscription au site
     * 
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        dump($user);

        if($form->isSubmitted() && $form->isValid()) {

            $user->setInscriptionDate(new \DateTime());

            $user->setNavColor("bg-dark");
            $user->setTableColor("bg-dark");
            $user->setBgColor("bg-white");
            $user->setNbArticlePerPage(5);
            $user->setNbCommentPerPage(5);

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            // Affichage d'un message de succès de l'inscription
            $this->addFlash(
                'success',
                "Bienvenue parmis nous ! Connectez vous pour consulter nos articles !"
                );
    
            // On redirige vers la page de connexion
            return $this->redirectToRoute('security_login');

        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

     /**
     *Permet d'afficher et de gérer le formulaire de connexion au site
     * 
     * @Route("/connexion", name="security_login") 
     * 
     */
    public function login(AuthenticationUtils $utils){

        // if ($this->getUser()) {
        //         return $this->redirectToRoute('admin_article');
        //     }
        

        $error = $utils->getLastAuthenticationError();
        // $username = $utils->getLastUsername();
        
        return $this->render('security/login.html.twig', [
            'hasError' => $error !== null,
            // 'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){

        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');

    }

}
