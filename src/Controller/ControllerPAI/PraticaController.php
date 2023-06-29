<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Pratica;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pratica')]
class PraticaController extends AbstractController
{

    #[Route('/{id}', name: 'app_pratica_show', methods: ['GET'])]
    public function show(Pratica $pratica): Response
    {
        return $this->render('pratica/show.html.twig', [
            'pratica' => $pratica,
        ]);
    }
}
