<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FooterController extends AbstractController
{
    #[Route('/footer', name: 'app_link_footer')]
    public function index(): Response
    {
        return $this->render('footer/pagina.html.twig', []);
    }
}