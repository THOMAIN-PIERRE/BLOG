<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;


class PaginationService {


    private $entityClass;

    // private $user = $this->getUser();
    //     // dump($user);
    //     $users = $manager->createQuery("SELECT u.nbArticlePerPage FROM App\Entity\Users u WHERE u.username = '$user'")->getSingleScalarResult();
    //     // dump($users);

    private $limit = 7;
    private $currentPage = 1;
    private $manager;
    
    
    public function __construct(EntityManagerInterface $manager){
        
        $this->manager = $manager;
    }


    public function getPages(){

        // 1) Connaître le total des enregistrements de la table

        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());


         // 2) Faire la division, l'arrondi et le renvoyer

        $pages = ceil($total / $this->limit);

        return $pages;

    }


    public function getData(){

        // 1) Calcul de l'offset (= start)

        $offset = $this->currentPage * $this->limit - $this->limit;

         // 2) Demander au repository de trouver les éléments

         $repo = $this->manager->getRepository($this->entityClass);

         $data = $repo->findBy([], [], $this->limit, $offset);


         // 3) Renvoyer les éléments en question

         return $data;

    }
    // Pagination et affichage de la liste des articles présentés du plus récent au plus ancien
    public function getBlog(){

        // 1) Calcul de l'offset (= start)

        $offset = $this->currentPage * $this->limit - $this->limit;

         // 2) Demander au repository de trouver les éléments

         $repo = $this->manager->getRepository($this->entityClass);

         $pierre = $repo->findBy([], ['createdAt' => "desc"], $this->limit, $offset);

         // 3) Renvoyer les éléments en question

         return $pierre;

    }

    // Pagination et affichage de la liste des évènements présentés du plus proche au plus lointain
    public function getEvent(){

        // 1) Calcul de l'offset (= start)

        $offset = $this->currentPage * $this->limit - $this->limit;

         // 2) Demander au repository de trouver les éléments

         $repo = $this->manager->getRepository($this->entityClass);

         $display = $repo->findBy([], ['date' => "asc"], $this->limit, $offset);

         // 3) Renvoyer les éléments en question

         return $display;

    }


     // Pagination et affichage dans l'administration de la liste des évènements présentés du plus proche au plus lointain
     public function getEventAdmin(){

        // 1) Calcul de l'offset (= start)

        $offset = $this->currentPage * $this->limit - $this->limit;

         // 2) Demander au repository de trouver les éléments

         $repo = $this->manager->getRepository($this->entityClass);

         $display = $repo->findBy([], ['date' => "asc"], $this->limit, $offset);

         // 3) Renvoyer les éléments en question

         return $display;

    }


    // Pagination et affichage de la liste des commentaires validés
    public function getCommentValide(){

        // 1) Calcul de l'offset (= start)

        $offset = $this->currentPage * $this->limit - $this->limit;

         // 2) Demander au repository de trouver la liste des commentaires validés

         $repo = $this->manager->getRepository($this->entityClass);

         $data = $repo->findBy(['status' => "Validé"], ['created_at' => 'desc'], $this->limit, $offset);

         // 3) Renvoyer les éléments en question

         return $data;

    }
    
    // Pagination et affichage de la liste des commentaires Non validés
    public function getCommentNonValide(){

        // 1) Calcul de l'offset (= start)

        $offset = $this->currentPage * $this->limit - $this->limit;

         // 2) Demander au repository de trouver la liste des commentaires Non validés

         $repo = $this->manager->getRepository($this->entityClass);

         $data = $repo->findBy(['status' => "Non validé"], ['created_at' => 'asc'], $this->limit, $offset);

         // 3) Renvoyer les éléments en question

         return $data;

    }

    // Pagination et affichage des articles du filtre Economy
    public function getEconomyArticles(){

        // 1) Calcul de l'offset (= start)

        $offset = $this->currentPage * $this->limit - $this->limit;

         // 2) Demander au repository de trouver la liste des commentaires Non validés

         $repo = $this->manager->getRepository($this->entityClass);

         $data = $repo->findBy(['category' => "Economie"], ['created_at' => 'desc'], $this->limit, $offset);

         // 3) Renvoyer les éléments en question

         return $data;

    }



    
    public function setPage($page){

        $this->currentPage = $page;

        return $this;

    }

    public function getPage(){

        return $this->currentPage;

    }

   
    public function setLimit($limit){

        $this->currentPage = $limit;

        return $this;

    }

    public function getLimit(){

        return $this->limit;
    }

    public function setEntityClass($entityClass){

        $this->entityClass = $entityClass;

        return $this;

    }

    public function getEntityClass(){

        return $this->entityClass;
    }

}
