<?php

namespace App\Repository;

use App\Entity\Article;
use App\Data\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Article::class);
        $this->paginator = $paginator;
    }


    /**
    * Pour récupèrer les articles en lien avec une recherche par catégorie ($search contient les catégories que nous avons cochées dans la liste de la barre latérale)
    // * @return Article[] Returns an array of Article objects
    * @return PaginationInterface
    */
    // public function searchArticles($criteria)
    public function findSearch(SearchData $search): PaginationInterface
    {
        //  $query = $this
        //     On récupère les articles
        //     ->createQueryBuilder('a')
        //     Je sélectionne les catégories ('c') et les articles ('a')
        //     ->select('c', 'a')
        //     On fait la jointure entre nos articles et les catégories. L'alias de la jointure sera 'c'
        //     ->join('a.category', 'c');

        //     if(!empty($search->q)) {
        //         $query = $query
        //             ->andWhere('a.title LIKE :q')
        //             ->setParameter('q', "%{$search->q}%");
        //             dump($query);
        //             die();
        //     }

        //     if(!empty($search->min)) {
        //         $query = $query
        //             ->andWhere('a.comments.rating LIKE :min')
        //             ->setParameter('min', "%{$search->min}%");
        //     }text-center align-middle

        //     if(!empty($search->max)) {
        //         $query = $query
        //             ->andWhere('a.comments.rating LIKE :max')
        //             ->setParameter('max', "%{$search->max}%");
        //     }

        //     if(!empty($search->promo)) {
        //         $query = $query
        //             ->andWhere('p.promo = 1');
        //     }


        //     if(!empty($search->categories)) {
        //         $query = $query
        //             ->andWhere('c.id IN (:categories)')
        //             ->setParameter('categories', $search->categories);
        //     }


        //     dump($query);
        //     die();


        // Renvoi un tableau de catégories
        // return $query->getQuery()->getResult();

        // return $this->findAll();
       
   
        // Voilà le retour de la fonction findByTitle
        // Je créé une requête pour retourner des articles
        // return $this->createQueryBuilder('a')
            // ->leftJoin('a.categories', 'category')
            // ->Where('category.title = :categoryTitle')
            // ->setParameter('categoryTitle', $criteria['categories']->getTitle())
            // // ->orderBy('a.created', 'DESC')
            // ->setMaxResults(10)
            // ->getQuery()
            // ->getResult()

            // return $this->createQueryBuilder('a')
            // ->join('a.category', 'c')
            // ->Where('category.title = :categoryTitle')
            // ->setParameter('categoryTitle', $criteria['categories']->getTitle())
            // // ->orderBy('a.created', 'DESC')
            // ->setMaxResults(10)
            // ->getQuery()
            // ->getResult();

            
            // if(!empty($criteria->categories)) {
                       
            //                 ->andWhere('c.id IN (:categories)')
            //                 ->setParameter('categories', $criteria->categories);
            //         }
            

        
            

            $query = $this
        
                ->createQueryBuilder('article')
                ->select('category', 'article')
                ->join('article.category', 'category');
                // ->select('comments', 'article')
                // ->join('article.comments', 'rating');
                // ->join('article.comments', 'comment');
            // return $query->getQuery()->getResult()->orderBy('article.createdAt', 'DESC');


            if(!empty($search->categories)) {

                $query = $query
                       
                ->andWhere('category IN (:categories)')
                ->orderBy('article.createdAt', 'DESC')
                ->setParameter('categories', $search->categories);
                // $query->getQuery()->getResult();
                // SELECT category, article FROM App\Entity\Article article INNER JOIN article.category category WHERE category IN (:categories)
                // dump($query);
       
            }
            

            // if(!empty($search->min)) {
            //             $query = $query
                        
            //                 ->andWhere('article.comments.rating >= :min')
            //                 // ->orderBy('article.createdAt', 'DESC')
            //                 ->setParameter('min', "%{$search->min}%");
            //         }
        
            //         if(!empty($search->max)) {
            //             $query = $query
            //                 ->andWhere('article.comments.rating <= :max')
            //                 // ->orderBy('article.createdAt', 'DESC')
            //                 ->setParameter('max', "%{$search->max}%");
            //         }


            if(!empty($search->q)) {
                        $query = $query
                            ->andWhere('article.title LIKE :q')
                            ->orderBy('article.createdAt', 'DESC')
                            ->setParameter('q', "%{$search->q}%");
                            // dump($query);
                            // die();
                            
                    }
                        


            // if(!empty($search->categories)) {
            //     $query = $query
            //         ->andWhere('category IN (:categories)')
            //         ->setParameter('categories', $search->categories);
            // }yoyo
            $query = $query->orderBy('article.createdAt', 'DESC')->getQuery();
            
            return $this->paginator->paginate(
                $query,
                $search->page,
                7
            );
    }
    

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

