<?php

namespace App\Controller;


use App\Entity\Carousel;
use App\Service\PaginationService;
use App\Form\AdminAjoutCarouselType;
use App\Repository\CarouselRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCarouselController extends AbstractController
{
      /**
     *Permet d'avoir accès à la liste des contenus du carousel dans l'administration
     *  
     * @Route("/admin/carousel/{page<\d+>?1}", name="admin_carousel")
     */
    public function index(CarouselRepository $repo, $page, PaginationService $pagination){

        $pagination->setEntityClass(Carousel::class)
                   ->setPage($page);
                   
        return $this->render('admin/carousel/index.html.twig', [
            'pagination' => $pagination
            
        ]);
    }

    /**
     *Permet d'afficher le formulaire de création de nouveau contenu pour le carousel dans l'administration
     * 
     * @Route("/admin/carousel/new", name="admin_carousel_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
        {
            $user = $this->getUser();

            $carousel = new Carousel();

            $form = $this->createForm(AdminAjoutCarouselType::class, $carousel);

            $form->handleRequest($request);


            if($form->isSubmitted() && $form->isValid()){

                $manager->persist($carousel);
                $manager->flush();
            }
                        
            return $this->render('admin/carousel/new.html.twig', [
                'form' => $form->createView()
            ]);
        }
    
     /**
     *Permet d'éditer des contenus du carousel dans l'administration
     * 
     * @Route("/admin/carousel/{id}/edit", name="admin_carousel_edit")
     * @param Carousel $carousel
     * @return Response
     */
    public function edit(Carousel $carousel, Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(AdminAjoutCarouselType::class, $carousel);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // $carousel->setCreatedAt(new \DateTime());
            // $carousel->setUtilisateurs($user);

            $manager->persist($carousel);
            $manager->flush();

            // return $this->redirectToRoute("admin_carousel");

            $this->addFlash(
            'success',
            "La diapositive n° <strong>{$carousel->getId()}</strong> a été modifiée !"
            );

            return $this->redirectToRoute("admin_carousel");
        }

        return $this->render('admin/carousel/edit.html.twig', [
                'carousel' => $carousel,
                'form' => $form->createView()
            ]);
    }
        

    /**
     *Permet de supprimer des articles dans l'administration
     * 
     * @Route("/admin/carousel/{id}/delete", name="admin_carousel_delete")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Carousel $carousel, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($carousel);
        $manager->flush();

        $this->addFlash(
            'success',
            "La ressource sélectionnée a été supprimée !"
            );

        return $this->redirectToRoute('admin_carousel');
    }

}
