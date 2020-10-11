<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\CustomizerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomizerController extends AbstractController
{
    /**
     * @Route("/customizer", name="customizer")
     */
    public function index()
    {
        return $this->render('customizer/index.html.twig', [
            
        ]);
    }


    /**
     *Permet d'éditer des paramètres de style de l'administration pour les modifier
     * 
     *  @Route("/customizer/{id}/edit", name="customizer_edit")
     * @param Users $users
     * @return Response
     */
    public function edit($id, Users $users, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CustomizerType::class, $users);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $manager->persist($users);
            $manager->flush();

            // $this->addFlash(
            // 'success',
            // "Les paramètres d'affichage de votre administration ont été modifiés"
            // );

            return $this->redirectToRoute("customizer");
        }

        return $this->render('customizer/edit.html.twig', [
                'form' => $form->createView(),
            ]);
    }
}
