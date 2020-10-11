<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Service\PaginationService;
use App\Form\AdminAjoutCommentType;
use App\Repository\CommentRepository;
use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
   /**
     *Permet d'avoir accès à la liste des commentaires validés dans l'administration
     *  
     * @Route("/admin/comment/{page<\d+>?1}", name="admin_comment")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination, StatsService $statsService){
        
        $pagination->setEntityClass(Comment::class)
                   ->setPage($page);

        $stats = $statsService->getStats();
                   
        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination,
            'stats' => $stats,
            
        ]);
    }

    /**
     *Permet d'avoir accès à la liste des commentaires à valider dans l'administration
     *  
     * @Route("/admin/comment/{page<\d+>?1}/toValidate", name="admin_comment_toValidate")
     */
    public function index2(CommentRepository $repo, $page, PaginationService $pagination){

        $pagination->setEntityClass(Comment::class)
                   ->setPage($page);
                   
        return $this->render('admin/comment/indexToValidate.html.twig', [
            'pagination' => $pagination,
            
        ]);
    }


     /**
     *Permet d'afficher le formulaire de création de commentaire dans l'administration
     * 
     * @Route("/admin/comment/new", name="admin_comment_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
        {
            $user = $this->getUser();

            $comment = new Comment();

            $form = $this->createForm(AdminAjoutCommentType::class, $comment);

            $form->handleRequest($request);


            if($form->isSubmitted() && $form->isValid()){
                // $manager = $this->getDoctrine()->getManager();
                $comment->setAuthor($this->getUser());
                $comment->setCreatedAt(new \DateTime());
                $comment->setUtilisateur($user);

                $manager->persist($comment);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Un nouveau commentaire à été publié !"
                    );
        
                    return $this->redirectToRoute("admin_comment");
            }
                        
            return $this->render('admin/comment/new.html.twig', [
                'form' => $form->createView()
            ]);
        }


    /**
     *Permet d'éditer des commentaires dans l'administration
     * 
     *  @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     * @param Comment $comment
     * @return Response
     */
    public function editPublishedComments(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(AdminAjoutCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $comment->setAuthor($this->getUser());
            //$comment->setCreatedAt(new \DateTime());
            // $comment->setUtilisateur($user);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
            'success',
            "Le commentaire n°<strong>{$comment->getId()} </strong> a été modifié !"
            );

            return $this->redirectToRoute("admin_comment");
        }

        return $this->render('admin/comment/edit.html.twig', [
                'comment' => $comment,
                'form' => $form->createView()
            ]);
    }
        

    /**
     *Permet de supprimer des commentaires dans l'administration
     * 
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire sélectionné a été supprimé !"
            );

        return $this->redirectToRoute('admin_comment');
    }
}
