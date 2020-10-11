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

    public function getArticlesStats($direction){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id
            FROM App\Entity\Comment c
            JOIN c.article a
            JOIN a.category u
            GROUP BY a
            ORDER BY note ' . $direction
        )
        ->setMaxResults(5)
        ->getResult();
    }

    // // Filtre d'affichage des articles par catégories
    // public function getEconomieArticles(){
    //     return $this->manager->createQuery("SELECT COUNT(c) FROM App\Entity\Article a WHERE c.category = 'Economie'")->getSingleScalarResult();
    // }

}
