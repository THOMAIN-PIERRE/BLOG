<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, StatsService $statsService)
    {
        //Le manager permet de créer des requetes en DQL (Doctrine Query Language)

        // $users = $statsService->getUsersCount();

        // $article = $statsService->getArticleCount();

        // $comment = $statsService->getCommentCount();


 
        // foreach article in article
        // $numberOfCharacter = $statsService->getCharacterCount();
        // dump($numberOfCharacter);
        
        // $utilisateur  = $manager->createQuery('SELECT CURRENT_USER')->getResult();
        // dump($utilisateur);
        // die();

        $user = $this->getUser();
        // dump($user);
        $users = $manager->createQuery("SELECT u.nbArticlePerPage FROM App\Entity\Users u WHERE u.username = '$user'")->getSingleScalarResult();
        // dump($users);
        // die();

    

        $stats = $statsService->getStats();

        $bestArticles = $statsService->getArticlesStats('DESC');

        $worstArticles = $statsService->getArticlesStats('ASC');

        // $bestArticles = $manager->createQuery(
        //         'SELECT AVG(c.rating) as note, a.title, a.id
        //         FROM App\Entity\Comment c
        //         JOIN c.article a
        //         JOIN a.category u
        //         GROUP BY a
        //         ORDER BY note DESC'
        //     )
        //     ->setMaxResults(10)
        //     ->getResult();

        //     dump($bestArticles);

        //     $worstArticles = $manager->createQuery(
        //         'SELECT AVG(c.rating) as note, a.title, a.id
        //         FROM App\Entity\Comment c
        //         JOIN c.article a
        //         JOIN a.category u
        //         GROUP BY a
        //         ORDER BY note ASC'
        //     )
        //     ->setMaxResults(10)
        //     ->getResult();

        //     dump($worstArticles);
           
           
        return $this->render('admin/dashboard/index.html.twig', [
            // 'stats' => compact('users', 'article', 'comment'), 
            'stats' => $stats,
            'bestArticles' => $bestArticles,
            'worstArticles' => $worstArticles,
        ]);
    }
}
