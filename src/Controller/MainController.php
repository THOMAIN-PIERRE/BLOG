<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([],['createdAt' => 'desc']);

        // $repo = $this->getDoctrine()->getRepository(Article::class);

        // $articles = $repo->findAll();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        
        return $this->render('main/home.html.twig', [
            'title' => "Bienvenue ici les amis !",
        ]);
    }

    /**
     * Création et modification d'un article
     * 
     * @Route("/main/new", name="main_create")
     * @Route("/main/{id}/edit", name="main_edit")
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {
        
        // $article = new Article();
        $user = $this->getUser();

        if(!$article){
            $article = new Article();
        }

        $form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('category')
                     ->add('content')
                     ->add('image')
                     ->getForm();

        //Ligne ajouté avec Bertrand pour appeller le formulaire que j'ai construit
        //$form = $this->createForm(ArticleType::class, $article);
        // dd($form);

        $form->handleRequest($request);

        dump($article);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()){
            $article->setCreatedAt(new \DateTime());
            $article->setUtilisateurs($user);
            

        }
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('main_show', ['id' => $article->getId()]);
        }
            return $this->render('main/create.html.twig', [
            'article' => $article,
            'formArticle' => $form->createView(),
            'editMode'=> $article->getId()!== null
            ]);
    }


    // /**
    // * @Route("/main/new", name="main_create")
    // */
    // public function create(Request $request, EntityManagerInterface $manager)
    // {
    //     $article = new Article();

    //     $form = $this->createForm(ArticleType::class, $article);

    //     $form->handleRequest($request);

    //     dump($article);

    //     if($form->isSubmitted() && $form->isValid()) {

    //         $article->setCreatedAt(new \DateTime());


    //         $manager->persist($article);
    //         $manager->flush();

    //         return $this->redirectToRoute('main_show', ['id' => $article->getId()]);
    //     }

    //     return $this->render('main/create.html.twig', [
    //         'formArticle' => $form->createView()
    //         ]);
    // }



    /**
     * //Pour montrer un article qui contient un formulaire de commentaire permettant d'ajouter des commentaires
     * 
     * @Route("/main/{id}", name="main_show")
     * 
     */
    public function show($id, Request $request, EntityManagerInterface $manager)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['id' => $id],['createdAt' => 'desc']);

        // $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy([
        //     'id' => $id,
        // ]);

        if(!$article){
            throw $this->createNotFoundException("L'article recherché n'existe pas");
        }

        //Instancier l'entité commentaire
        $comment = new Comment();

        $user = $this->getUser();


        //Créer l'objet formulaire
        $form = $this->createForm(CommentType::class, $comment);

        //Récupération des données saisies
        $form->handleRequest($request);

        //Vérifier l'envoi du formulaire et si les données sont valides
        if($form->isSubmitted() && $form->isValid()) {

            //Le formulaire a été envoyé et les données sont valides

            $comment->setArticle($article)
                    ->setCreatedAt(new \DateTime())
                    ->setAuthor($this->getUser())
                    ->setUtilisateur($user);


        //On hydrate l'objet en y ajoutant les données  
            $manager->persist($comment);
        //On écrit dans la BDD
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire à bien été pris en compte !"
            );
        }

        $repo = $this->getDoctrine()->getRepository(Article::class);

        $article = $repo->find($id);

        return $this->render('main/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

}
   

    

