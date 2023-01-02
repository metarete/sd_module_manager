<?php

namespace App\Controller;

use App\Entity\Paziente;
use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/scadenzario')]
class ScadenzarioController extends AbstractController
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/{page}', name: 'app_scadenzario_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index( int $page = 1): Response
    {

        $em = $this->entityManager;
        $schedaPAIRepository = $em->getRepository(SchedaPAI::class);
        //assistiti
        $assistitiRepository = $em->getRepository(Paziente::class);
        $assistiti = $assistitiRepository->findAll();
        //controllo login
        $user = $this->getUser();



        //parametri per calcolo tabella
        $ruoloUser = $user->getRoles();
        $idUser = $user->getId();
        //filtri
        $numeroSchedeVisibiliPerPagina = 200;


        //calcolo tabella
        $schedaPais = null;

        $schedePerPagina = $numeroSchedeVisibiliPerPagina;

        $offset = $schedePerPagina * $page - $schedePerPagina;


        if ($ruoloUser[0] == "ROLE_ADMIN") {
           
            $schedaPais = $schedaPAIRepository->findBy([], array('id' => 'ASC'), $schedePerPagina, $offset);
                
        }
        else if ($ruoloUser[0] == "ROLE_USER") {
            $schedaPais = $schedaPAIRepository->findUserSchedePai($idUser, null, $schedePerPagina, $page); 
        }
        
        return $this->render('scadenzario/index.html.twig', [
            'scheda_pais' => $schedaPais,
            'pagina' => $page,
            'schede_per_pagina' => $schedePerPagina,
            'user' => $user,
            'assistiti' => $assistiti,
        ]);
    }
}