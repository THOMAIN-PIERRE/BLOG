<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\AdminAjoutCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{



    /**
     *Permet d'avoir accès à la liste des commentaires dans l'administration
     *  
     * @Route("/admin/comment", name="admin_comment")
     */
    public function index(CommentRepository $repo)
    {
        $repo = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repo->findAll();

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments
        ]);
    }


     /**
     *Permet d'afficher le formulaire de création d'articles dans l'administration
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
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $user = $this->getUser();

        $form = $this->createForm(AdminAjoutCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new \DateTime());
            $comment->setUtilisateur($user);

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute("admin_comment");

            $this->addFlash(
            'success',
            "Le commentaire a bien été modifié !"
            );
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
