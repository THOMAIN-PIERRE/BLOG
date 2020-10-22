<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class InformationsController extends AbstractController
{
    /**
     * Affichage la page des conditions générales d'utilisation du site
     * 
     * @Route("/informations", name="cgu")
     */
    public function cgu()
    {
       

        return $this->render('informations/cgu.html.twig', [
            
        ]);
    }


    /**
     * Affichage des mentions légales du site
     * 
     * @Route("/informations/mentionsLegales", name="mentionsLegales")
     */
    public function mentionsLegales()
    {
       

        return $this->render('informations/mentionsLegales.html.twig', [
            
        ]);
    }


    /**
     * Affichage de la politique de confidentialité du site
     * 
     * @Route("/informations/politiqueConfidentialite", name="politiqueConfidentialite")
     */
    public function politiqueConfidentialite()
    {
       

        return $this->render('informations/politiqueConfidentialite.html.twig', [
            
        ]);
    }
}