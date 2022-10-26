<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/homepage', name: 'app_homepage')]
    public function index(): Response
    {
        $user= $this-> getUser();

        return $this->render('Homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'user' => $user
        ]);
    }
}
