<?php

namespace App\Controller;

use App\Entity\Paziente;
use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function index( Request $request, int $page = 1 ): Response
    {
        
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        //assistiti
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        $assistiti = $assistitiRepository->findAll();
        //controllo login
        $user = $this->getUser();



        //parametri per calcolo tabella
        $roles = $user->getRoles();
        $idUser = $user->getId();
        //filtri
        $numeroSchedeVisibiliPerPagina = 200;


        //calcolo tabella
        $schedaPais = null;

        $schedePerPagina = $numeroSchedeVisibiliPerPagina;

        $offset = $schedePerPagina * $page - $schedePerPagina;


        if (in_array("ROLE_ADMIN", $roles)) {
           
            $schedaPais = $schedaPAIRepository->findBy([], array('id' => 'ASC'), $schedePerPagina, $offset);
                
        }
        else {
            $schedaPais = $schedaPAIRepository->findUserSchedePai($idUser, null, $schedePerPagina, $page); 
        }
        
        //setto pagina di partenza
        $pathName = 'app_scadenzario_index';
        

         //calcolo valori delle schede per le scadenze delle scale -> nel listener
        
         //alert
         $session = $request->getSession();
         $alertSincronizzazione = $session->get('alertSincronizzazione');
         if ($alertSincronizzazione == 'completata') {
             $this->addFlash(
                 'Successo',
                 'Sincronizzazione completata con successo!'
             );
         }
         elseif($alertSincronizzazione == 'errore'){
             $this->addFlash(
                 'Fallimento',
                 'Sincronizzazione fallita!'
             );
         }
         elseif($alertSincronizzazione == 'chiusuraFallita'){
             $this->addFlash(
                 'Fallimento',
                 'Chiusura Fallita! Per chiudere una scheda è necessario aver compilato tutte le
                 scale di valutazione necessarie, la chisura servizio e almeno una valutazione professionale 
                 per operatore coinvolto'
             );
         }
         elseif($alertSincronizzazione == 'chiusuraCompletata'){
            $this->addFlash(
                'Successo',
                'Chiusura Completata con successo!'
            );
        }
         elseif($alertSincronizzazione == 'approvazioneFallita'){
             $this->addFlash(
                 'Fallimento',
                 'Impossibile approvare la scheda. Per approvare la scheda è necessario impostare un
                 operatore principale andando in configura'
             );
         }
         $session->set('alertSincronizzazione', '');

        

        return $this->render('scadenzario/index.html.twig', [
            'scheda_pais' => $schedaPais,
            'pagina' => $page,
            'schede_per_pagina' => $schedePerPagina,
            'user' => $user,
            'assistiti' => $assistiti,
            'pathName' => $pathName,
            
            
        ]);
    }
}