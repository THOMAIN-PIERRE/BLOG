<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AdminAjoutUserType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminUsersController extends AbstractController
{
     /**
     *Permet d'avoir accès à la liste des utilisateurs dans l'administration
     *  
     * @Route("/admin/users", name="admin_users")
     */
    public function index(UsersRepository $repo)
    {
        $repo = $this->getDoctrine()->getRepository(Users::class);

        $users = $repo->findAll();

        return $this->render('admin/users/index.html.twig', [
            'users' => $users
        ]);
    }

     /**
     *Permet d'afficher le formulaire de création d'utilisateurs dans l'administration
     * 
     * @Route("/admin/users/new", name="admin_users_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
        {
            // $user = $this->getUser();

            $users = new Users();

            $form = $this->createForm(AdminAjoutUserType::class, $users);

            $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            //$users->setCreatedAt(new \DateTime());
            // $users->setUtilisateurs($user);

                $manager->persist($users);
                $manager->flush();
            }
                        
            return $this->render('admin/users/new.html.twig', [
                'form' => $form->createView()
            ]);
        }

}
