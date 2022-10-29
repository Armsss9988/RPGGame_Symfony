<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $hasAccess = $this->isGranted('ROLE_USER');
        if($hasAccess)
        {
            return $this->redirectToRoute('app_game_index');
        }
        return $this->render('home/index.html.twig');
    }
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if($hasAccess)
        {
            return $this->render('home/dashboard.html.twig');
        }
        return $this->render('home/index.html.twig');
    }
}
