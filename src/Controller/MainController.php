<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Data\SearchData;
use App\Entity\Carousel;
use App\Form\SearchType;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Service\StatsService;
use App\Service\PaginationService;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    // /**
    //  * Affichage de la liste des articles dans le fil d'actualité
    //  * 
    //  * @Route("/main", name="main")
    //  */
    // public function index()
    // {
    //     $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([],['createdAt' => 'desc']);

        // $repo = $this->getDoctrine()->getRepository(Article::class);

        // $articles = $repo->findAll();

    //     return $this->render('main/index.html.twig', [
    //         'articles' => $articles
    //     ]);
    // }

    /**
     *Permet d'avoir accès à la liste des articles dans le fil d'actualité
     *  
     * @Route("/main/{page<\d+>?1}", name="main")
     */
    public function index($page, PaginationService $pagination, StatsService $statsService, Request $request, ArticleRepository $repository){

        // Filtre Article Symfony :
        // J'initialise mes données
        // $data = new SearchData();
        //Je créé le formulaire. Il utilisera une classe SearchType et les données $data
        // $form = $this->createForm(SearchType::class, $data);
        // Permet de modifier l'objet $data qui représente mes données. Gère la requête qui lui est passsée en paramètre
        // $form->handleRequest($request);
        // $articles = $repository->findSearch($data);
        // dump($data);
        // die();

        $search = new SearchData();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(SearchType::class, $search);

        // Pour récupérer le formulaire (et les données recherchées indiquées dedans) du côté du controller
        $form->handleRequest($request);
    
            // Je déclare la méthode findSearch() qui va me permettre d'extraire ce que je veux de la BDD (= ce qui correspond a mes données de recherche) et qui va reçoit les critères de recherche saisis dans le menu de filtration
            
            // dump($criteria);
            // die();
            $articles = $repository->findSearch($search);
            // dump($search);
            // dump($articles);
            // die();

        
        // On récupère les commentaires validés de l'article
        // $articles = $this->getDoctrine()->getRepository(Article::class)->findBy([],['createdAt' => 'desc']);
        
        // $article = $this->getDoctrine()->getRepository(Article::class)->findAll();
        // dump($article);

        // $article = $this->getDoctrine()->getRepository(Article::class)->findAll(['article']);

        // $commentaires = $this->getDoctrine()->getRepository(Comment::class)->findBy(['article' => $article,  'status' => "Validé"],['created_at' => 'desc']);
        // dump($commentaires2);

        // $stats = $statsService->getStats();

        // $pagination->setEntityClass(Article::class)
        //            ->setPage($page);
                   
        return $this->render('main/index.html.twig', [
            // 'pagination' => $pagination,
            // 'commentaires2' => $commentaires2,
            // 'article' => $article,
            // 'commentaires' => $commentaires,
            // 'stats' => $stats,

            'articles' => $articles,
            // J'envoie le formulaire à ma vue
            'form' => $form->createView()
        ]);
    }


    /**
     * Point d'entrée de notre site (front controller)
     * 
     * @Route("/", name="home")
     */
    public function home(StatsService $statsService)
    {
        $repo = $this->getDoctrine()->getRepository(Carousel::class);

        $carousel = $repo->findAll();

        $article = new Article();

        $stats = $statsService->getStats();

        $HomeBestArticles = $statsService->getHomeArticlesStats('DESC');

        $MostCommentedArticles = $statsService->getHomeCommentsStats('DESC');

        $TotalCommentsPerPerson = $statsService->getHomeCommentsStatsPerPerson('DESC');

        return $this->render('main/home.html.twig', [
            'carousel' => $carousel,
            'article' => $article,
            'stats' => $stats,
            'HomeBestArticles' => $HomeBestArticles,
            'MostCommentedArticles' => $MostCommentedArticles,
            'TotalCommentsPerPerson' => $TotalCommentsPerPerson
        ]);
    }

    /**
     * Création d'un article dans le fil d'actualité
     * 
     * @Route("/main/new", name="main_create")
     * @Route("/main/{id}/edit", name="main_edit")
     * @IsGranted("ROLE_EDITOR")
     * 
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

            $this->addFlash(
                'success',
                "Votre modification a été prise en compte !"
                );

            return $this->redirectToRoute('account_publications');
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
    //         ]);(article.comments | length) - (
    // }



    /**
     * //Pour montrer un article qui contient un formulaire de commentaire permettant d'ajouter des commentaires
     * 
     * @Route("/main/{id}", name="main_show")
     * 
     */
    public function show($id, Request $request, EntityManagerInterface $manager)
    {
        // On récupère l'article correspondant à l'id sélectionné
        $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy(['id' => $id]);

        // $article = $this->getDoctrine()->getRepository(Article::class)->findOneBy([
        //     'id' => $id,
        // ]);
        

        // On récupère les commentaires validés de l'article
        $commentaires = $this->getDoctrine()->getRepository(Comment::class)->findBy(['article' => $article, 'status' => "Validé"],['created_at' => 'desc']);
        dump($commentaires);
        // die();
       


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
                    ->setUtilisateur($user)
                    ->setStatus("Non validé");


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
            'commentForm' => $form->createView(),
            'commentaires' => $commentaires
        ]);
    }







    // /**
    // * 
    // * @Route("/main", name="main")
    // *
    // */
    // public function searchArticle(Request $request)
    // {
    //     $form = $this->createForm(SearchType::class);

    //     return $this->render('main/index.html.twig', [

    //     // J'envoie le formulaire à ma vue
    //     'form' => $form->createView(),

    //     ]);
    // }

}
   

    

