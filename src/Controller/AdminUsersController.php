<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Users;
use App\Form\AdminAjoutUserType;
use App\Service\PaginationService;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUsersController extends AbstractController
{
      /**
     *Permet d'avoir accès à la liste des utilisateurs dans l'administration
     *  
     * @Route("/admin/users/{page<\d+>?1}", name="admin_users")
     */
    public function index(UsersRepository $repo, $page, PaginationService $pagination){

        $repo = $this->getDoctrine()->getRepository(Users::class);

        $users = $repo->findAll();

        $pagination->setEntityClass(Users::class)
                   ->setPage($page);
                   
        return $this->render('admin/users/index.html.twig', [
            'pagination' => $pagination,
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
    public function create(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
        {
            $user = $this->getUser();

            $users = new Users();
            // $adminRole = new Role();

            // $adminRole ->setIntitule('ROLE_ADMIN');
            // $role = $this->getParameter('security.role_hierarchy.role');

            $form = $this->createForm(AdminAjoutUserType::class, $users);

            $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            //$users->setCreatedAt(new \DateTime());
            // $users->setUtilisateurs($user);

                $manager->persist($users);
                // $manager->persist($adminRole);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'utilisateur <strong>{$users->getUsername()} </strong> a été créé !"
                    );
        
                    return $this->redirectToRoute("admin_users");
            }
                        
            return $this->render('admin/users/new.html.twig', [
                'form' => $form->createView(),
                // 'adminRole' => $adminRole
            ]);
        }

    /**
     *Permet d'éditer des utilisateurs dans l'administration
     * 
     *  @Route("/admin/users/{id}/edit", name="admin_users_edit")
     * @param Users $users
     * @return Response
     */
    public function edit($id, Users $users, Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(AdminAjoutUserType::class, $users);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($users);
            $manager->flush();

            $this->addFlash(
            'success',
            "Les informations de l'utilisateur <strong>{$users->getUsername()} </strong> ont été modifiées !"
            );

            return $this->redirectToRoute("admin_users");
        }

        return $this->render('admin/users/edit.html.twig', [
                'form' => $form->createView(),
            ]);
    }
      

    // /**
    //  *Permet de supprimer des utilisateurs dans l'administration
    //  * 
    //  * @Route("/admin/users/{id}/delete", name="admin_users_delete")
    //  * @param EntityManagerInterface $manager
    //  * @return Response
    //  */
    // public function delete($id, Users $users, Request $request, EntityManagerInterface $manager)
    // {   
        // $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(['id' => $id]);
        
        // $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy(['id' => $id]);

        // $user->removeRole($role);

        // $role->removeUsers($user);

        // $user->remove($users);
        // $manager->flush();

        // $this->addFlash(
        //     'success',
        //     "L'utilisateur <strong>{$users->getUsername()} </strong> a été supprimé !"
        //     );

        // return $this->redirectToRoute('admin_users');
 
    // }
}
