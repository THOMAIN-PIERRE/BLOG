<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager)
    {
        //Le manager permet de créer des requetes en DQL (Doctrine Query Language)

        $users = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\Users u')->getSingleScalarResult();




        return $this->render('admin/dashboard/index.html.twig', [
            'users' => '$users',
        ]);
    }
}
