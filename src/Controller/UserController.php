<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_menu_show")
     */
    public function index(Users $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
