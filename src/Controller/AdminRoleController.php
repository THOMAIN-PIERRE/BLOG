<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\AdminAjoutRoleType;
use App\Repository\RoleRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminRoleController extends AbstractController
{
    /**
     * Permet d'avoir accès à la liste des rôles dans l'administration
     * 
     * @Route("/admin/role{page<\d+>?1}", name="admin_role")
     */
    public function index(RoleRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Role::class)
        ->setPage($page);
        
    return $this->render('admin/role/index.html.twig', [
        'pagination' => $pagination
 
        ]);
    }

    /**
     *Permet d'afficher le formulaire de création de rôle dans l'administration
     * 
     * @Route("/admin/role/new", name="admin_role_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
        {
            $user = $this->getUser();

            $role = new Role();

            $form = $this->createForm(AdminAjoutRoleType::class, $role);

            $form->handleRequest($request);


            if($form->isSubmitted() && $form->isValid()){
                

                $manager->persist($role);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Le rôle <strong>{$role->getIntitule()} </strong>a été ajouté !"
                    );
        
                    return $this->redirectToRoute("admin_role");
            }
                        
            return $this->render('admin/role/new.html.twig', [
                'form' => $form->createView()
            ]);
        }


    /**
     *Permet d'éditer des rôles dans l'administration
     * 
     *  @Route("/admin/role/{id}/edit", name="admin_role_edit")
     * @param Role $role
     * @return Response
     */
    public function edit(Role $role, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(AdminAjoutRoleType::class, $role);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($user);
            $manager->persist($role);
            $manager->flush();

            $this->addFlash(
            'success',
            "Le rôle <strong>{$role->getIntitule()} </strong> a été modifié !"
            );

            return $this->redirectToRoute("admin_role");
        }

        return $this->render('admin/role/edit.html.twig', [
                'article' => $role,
                'form' => $form->createView()
            ]);
    }
        

    /**
     *Permet de supprimer des rôles dans l'administration
     * 
     * @Route("/admin/role/{id}/delete", name="admin_role_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Role $role, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($role);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le rôle <strong>{$role->getIntitule()} </strong> a été supprimé !"
            );

        return $this->redirectToRoute('admin_role');
    }

}
