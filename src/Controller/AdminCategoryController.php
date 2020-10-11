<?php

namespace App\Controller;

use App\Entity\Category;
use App\Service\PaginationService;
use App\Form\AdminAjoutCategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCategoryController extends AbstractController
{
    /**
     *Permet d'avoir accès à la liste des categories dans l'administration
     *  
     * @Route("/admin/category/{page<\d+>?1}", name="admin_category")
     */
    public function index(CategoryRepository $repo, $page, PaginationService $pagination){

        $pagination->setEntityClass(Category::class)
                   ->setPage($page);
                   
        return $this->render('admin/category/index.html.twig', [
            'pagination' => $pagination
            
        ]);
    }

    /**
     *Permet d'afficher le formulaire de création de catégorie dans l'administration
     * 
     * @Route("/admin/category/new", name="admin_category_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
        {
            $category = new Category();

            $form = $this->createForm(AdminAjoutCategoryType::class, $category);

            $form->handleRequest($request);

    

            if($form->isSubmitted() && $form->isValid()){
                // $manager = $this->getDoctrine()->getManager();

                $manager->persist($category);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "La nouvelle catégorie <strong>{$category->getTitle()} </strong>a été ajoutée !"
                    );
        
                    return $this->redirectToRoute("admin_category");
            }
                        
            return $this->render('admin/category/new.html.twig', [
                'form' => $form->createView()
            ]);

        }

    /**
     *Permet d'éditer des catégories dans l'administration
     * 
     *  @Route("/admin/category/{id}/edit", name="admin_category_edit")
     * @param Category $category
     * @return Response
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminAjoutCategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
            'success',
            "La cétégorie n°<strong>{$category->getId()} </strong> a été modifiée !"
            );

            return $this->redirectToRoute("admin_category");
        }

        return $this->render('admin/category/edit.html.twig', [
                'category' => $category,
                'form' => $form->createView()
            ]);
    }
      

    /**
     *Permet de supprimer des categories dans l'administration
     * 
     * @Route("/admin/category/{id}/delete", name="admin_category_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Category $category, Request $request, EntityManagerInterface $manager)
    {   
        $manager->remove($category);
        $manager->flush();

        $this->addFlash(
            'success',
            "La catégorie <strong>{$category->getTitle()}</strong> a été supprimée avec succès !"
            );

        return $this->redirectToRoute('admin_category');
 
    }
}
