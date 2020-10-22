<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class StatsService {

    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;

    }

    public function getStats(){

        $users = $this->getUsersCount();

        $article = $this->getArticleCount();

        $comment = $this->getCommentCount();

        $waitingComment = $this->getWaitingCommentCount();

        $validatedCommentCount = $this->getValidatedCommentCount();

        $commentRealTotal = $comment-$waitingComment;

        return compact('users', 'article', 'comment', 'waitingComment', 'commentRealTotal', 'validatedCommentCount');

    }    

    public function getUsersCount(){
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\Users u')->getSingleScalarResult();
    }

    public function getArticleCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Article a')->getSingleScalarResult();
    }

    public function getCommentCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getWaitingCommentCount(){
        return $this->manager->createQuery("SELECT COUNT(c) FROM App\Entity\Comment c WHERE c.status = 'Non validé'")->getSingleScalarResult();
    }

    
    public function getValidatedCommentCount(){
        return $this->manager->createQuery("SELECT COUNT(c) FROM App\Entity\Comment c WHERE c.status = 'Validé' AND c.article = '33'")->getSingleScalarResult();
    }
   
    // $users = $manager->createQuery("SELECT u.nbArticlePerPage FROM App\Entity\Users u WHERE u.username = '$user'")->getSingleScalarResult();


    

    // $users = $manager->createQuery("SELECT u.nbArticlePerPage FROM App\Entity\Users u WHERE u.username = '$user'")->getSingleScalarResult();

  
    // public function getTest(){
    //     return $this->manager->createQuery("SELECT nbArticlePerPage FROM App\Entity\Users u WHERE u.username = CURRENT_USER")->getSingleScalarResult();
    // }

    public function getHomeArticlesStats($direction){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.content, a.image, a.id, a.createdAt, u.username, u.avatar
            FROM App\Entity\Comment c
            JOIN c.article a
            JOIN a.utilisateurs u
            GROUP BY a
            ORDER BY note ' . $direction
        )
        ->setMaxResults(3)
        ->getScalarResult();
    }



    public function getArticlesStats($direction){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.username, u.avatar
            FROM App\Entity\Comment c
            JOIN c.article a
            JOIN a.utilisateurs u
            GROUP BY a
            ORDER BY note ' . $direction
        )
        ->setMaxResults(5)
        ->getResult();
    }



    public function getAdminCommentsStats($direction){
        return $this->manager->createQuery(
            "SELECT Count(c.Author) as number, a.title, a.id, a.image, a.content, u.username, u.avatar
            FROM App\Entity\Comment c
            JOIN c.article a
            JOIN a.utilisateurs u
            WHERE c.status = 'Validé'
            GROUP BY a
            ORDER BY number " . $direction
        )
        ->setMaxResults(5)
        ->getResult();
    }



    public function getHomeCommentsStats($direction){
        return $this->manager->createQuery(
            "SELECT Count(c.Author) as number, a.title, a.id, a.image, a.createdAt, a.content, u.username, u.avatar
            FROM App\Entity\Comment c
            JOIN c.article a
            JOIN a.utilisateurs u
            WHERE c.status = 'Validé'
            GROUP BY a
            ORDER BY number " . $direction
        )
        ->setMaxResults(3)
        ->getResult();
    }



    // public function getAdminCommentsStatsPerPerson($direction){
    //     return $this->manager->createQuery(
    //         "SELECT Count(c.Author) as num, u.avatar, u.username
    //         FROM App\Entity\Comment c
    //         JOIN c.utilisateur u
    //         WHERE c.status = 'Validé'
    //         GROUP BY u
    //         ORDER BY num " . $direction
           
    //     )
    //     ->setMaxResults(100)
    //     ->getResult();

    // }        



    public function getAdminCommentsStatsPerPerson($direction){
        return $this->manager->createQuery(
            "SELECT Count(c.Author) as num, u.avatar, u.username
            FROM App\Entity\Comment c
            JOIN c.utilisateur u
            WHERE c.status = 'Validé'
            GROUP BY u
            ORDER BY num " . $direction
           
        )
        ->setMaxResults(100)
        ->getResult();
    
    }


    
    public function getHomeCommentsStatsPerPerson($direction){
        return $this->manager->createQuery(
            "SELECT Count(c.Author) as num, u.avatar, u.username
            FROM App\Entity\Comment c
            JOIN c.utilisateur u
            WHERE c.status = 'Validé'
            GROUP BY u
            ORDER BY num " . $direction
           
        )
        ->setMaxResults(3)
        ->getResult();
    }



    public function getEvents($direction){
        return $this->manager->createQuery(
            "SELECT e.date as date, e.name, e.subtitle, e.place, e.picture, e.description, e.content, e.id
            FROM App\Entity\Event e
            WHERE e.date >= CURRENT_DATE()
            ORDER BY date " . $direction
               
        )
        ->setMaxResults(10)
        ->getResult();
    
    }


    public function getCountEvents($direction){
        return $this->manager->createQuery(
            "SELECT Count(e.date) as date
            FROM App\Entity\Event e
            WHERE e.date >= CURRENT_DATE()
            ORDER BY date " . $direction
               
        )
        
        ->getSingleScalarResult();
    
    }
    
    
        // public function getCommentsStatsPerPerson($direction){
        // return $this->manager->createQuery(
        //     "SELECT Count(c.Author) as num, u.avatar, u.username
        //     FROM App\Entity\Comment c
        //     JOIN c.utilisateur u
        //     WHERE c.status = 'Validé'
        //     GROUP BY u
        //     ORDER BY num " . $direction
           
        // )
        // ->setMaxResults(10)
        // ->getResult();
}


 

    // // Filtre d'affichage des articles par catégories
    // public function getEconomieArticles(){
    //     return $this->manager->createQuery("SELECT COUNT(c) FROM App\Entity\Article a WHERE c.category = 'Economie'")->getSingleScalarResult();
    // }


