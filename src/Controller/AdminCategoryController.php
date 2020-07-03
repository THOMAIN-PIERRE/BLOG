<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\AdminAjoutCategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController
{
    /**
     *Permet d'avoir accès à la liste des commentaires dans l'administration
     *  
     * @Route("/admin/category", name="admin_category")
     */
    public function index(CategoryRepository $repo)
    {
        $repo = $this->getDoctrine()->getRepository(Category::class);

        $categories = $repo->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
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

            return $this->redirectToRoute("admin_category");

            $this->addFlash(
            'success',
            "La modification a bien été prise en compte !"
            );
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
            "La catégorie a été supprimée avec succès !"
            );

        return $this->redirectToRoute('admin_category');
 
    }
}
