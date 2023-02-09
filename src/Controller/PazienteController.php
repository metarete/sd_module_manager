<?php

namespace App\Controller;

use App\Entity\Paziente;
use App\Repository\PazienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paziente')]
class PazienteController extends AbstractController
{
    #[Route('/', name: 'app_paziente_index', methods: ['GET'])]
    public function index(PazienteRepository $pazienteRepository): Response
    {
        return $this->render('paziente/index.html.twig', [
            'pazientes' => $pazienteRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_paziente_show', methods: ['GET'])]
    public function show(Paziente $paziente): Response
    {
        return $this->render('paziente/show.html.twig', [
            'paziente' => $paziente,
        ]);
    }

}
