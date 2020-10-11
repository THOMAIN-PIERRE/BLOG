<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AdminCustomizerType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCustomizerController extends AbstractController
{
   /**
     * Permet d'afficher la page des paramètres de style de l'administration
     * 
     * @Route("/admin/customizer", name="admin_customizer")
     */
    public function index()
    {
        return $this->render('admin/customizer/index.html.twig', [
            
        ]);
    }

    /**
     *Permet d'éditer des paramètres de style de l'administration pour les modifier
     * 
     *  @Route("/admin/customizer/{id}/edit", name="admin_customizer_edit")
     * @param Users $users
     * @return Response
     */
    public function edit($id, Users $users, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCustomizerType::class, $users);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($users);
            $manager->flush();

            // $this->addFlash(
            // 'success',
            // "Les paramètres d'affichage de votre administration ont été modifiés"
            // );

            return $this->redirectToRoute("admin_customizer");
        }

        return $this->render('admin/customizer/edit.html.twig', [
                'form' => $form->createView(),
            ]);
    }
}
