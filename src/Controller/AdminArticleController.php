<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\AdminProduitType;
use App\Service\PaginationService;
use App\Form\AdminAjoutArticleType;
use App\Form\AdminAjoutProduitType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminArticleController extends AbstractController
{
    /**
     *Permet d'avoir accès à la liste des articles dans l'administration
     *  
     * @Route("/admin/article/{page<\d+>?1}", name="admin_article")
     */
    public function index(ArticleRepository $repo, $page, PaginationService $pagination){

        $pagination->setEntityClass(Article::class)
                   ->setPage($page);
                   
        return $this->render('admin/article/index.html.twig', [
            'pagination' => $pagination
            
        ]);
    }

    // /**
    //  *Permet d'avoir accès à la liste des articles parlant d'économie dans l'administration
    //  *  
    //  * @Route("/admin/article/{page<\d+>?1}", name="admin_article")
    //  */
    // public function EconomyArticles(ArticleRepository $repo, $page, PaginationService $pagination){

    //     $pagination->setEntityClass(Article::class)
    //                ->setPage($page);
                   
    //     return $this->render('admin/article/index.html.twig', [
    //         'pagination' => $pagination
            
    //     ]);
    // }


     /**
     * Permet d'afficher le formulaire de création d'articles dans l'administration
     * 
     * @Route("/admin/article/new", name="admin_article_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
        {
            $user = $this->getUser();

            $article = new Article();

            $form = $this->createForm(AdminAjoutArticleType::class, $article);

            $form->handleRequest($request);


            if($form->isSubmitted() && $form->isValid()){
                // $manager = $this->getDoctrine()->getManager();
                $article->setCreatedAt(new \DateTime());
                $article->setUtilisateurs($user);

                $manager->persist($article);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "L'article intitulé <strong>{$article->getTitle()}</strong> a été créé !"
                    );
        
                    return $this->redirectToRoute("admin_article");
            }
                        
            return $this->render('admin/article/new.html.twig', [
                'form' => $form->createView()
            ]);
        }


    /**
     *Permet d'éditer des articles dans l'administration
     * 
     *  @Route("/admin/article/{id}/edit", name="admin_article_edit")
     * @param Article $article
     * @return Response
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(AdminAjoutArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $article->setCreatedAt(new \DateTime());
            $article->setUtilisateurs($user);
            $article->setUpdatedAt(new \DateTime());
            


            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
            'success',
            "L'article intitulé <strong>{$article->getTitle()}</strong> a été modifié !"
            );

            return $this->redirectToRoute("admin_article");
        }

        return $this->render('admin/article/edit.html.twig', [
                'article' => $article,
                'form' => $form->createView()
            ]);
    }
        

    /**
     *Permet de supprimer des articles dans l'administration
     * 
     * @Route("/admin/article/{id}/delete", name="admin_article_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Article $article, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($article);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'article intitulé <strong>{$article->getTitle()}</strong> a été supprimé !"
            );

        return $this->redirectToRoute('admin_article');
    }
}
