<?php

namespace App\Controller;

use App\Entity\Paziente;
use App\Entity\EntityPAI\SchedaPAI;
use App\Twig\FiltroColoriScadenzario;
use App\Twig\FiltroDropdownScadenzario;
use App\Twig\FiltroNomiStatiScadenzario;
use App\Twig\FiltroSimboloValutazioneScadenzario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/scadenzario')]
class ScadenzarioController extends AbstractController
{
    private $entityManager;
    private $filtroColoriScadenzario;
    private $filtroNomiStatiScadenzario;
    private $filtroDropdownScadenzario;
    private $filtroSimboloValutazioneScadenzario;

    public function __construct(EntityManagerInterface $entityManager, FiltroColoriScadenzario $filtroColoriScadenzario, FiltroNomiStatiScadenzario $filtroNomiStatiScadenzario, FiltroDropdownScadenzario $filtroDropdownScadenzario, FiltroSimboloValutazioneScadenzario $filtroSimboloValutazioneScadenzario)
    {
        $this->entityManager = $entityManager;
        $this->filtroColoriScadenzario = $filtroColoriScadenzario;
        $this->filtroNomiStatiScadenzario = $filtroNomiStatiScadenzario;
        $this->filtroDropdownScadenzario = $filtroDropdownScadenzario;
        $this->filtroSimboloValutazioneScadenzario = $filtroSimboloValutazioneScadenzario;
    }


    #[Route('/{page}', name: 'app_scadenzario_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, int $page = 1): Response
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        //controllo login
        $user = $this->getUser();

        //parametri per calcolo tabella
        $roles = $user->getRoles();
        $idUser = $user->getId();
        //sessione
        $session = $request->getSession();
        //filtri
        if( $request->request->get('filtro_numero_schede_scadenzario') != null || $request->request->get('filtro_numero_schede_scadenzario') == $session->get('filtro_numero_schede_scadenzario')){
            if($request->request->get('filtro_numero_schede_scadenzario') == 0)
                $session->set('filtro_numero_schede_scadenzario', null);
            else{
                $session->set('filtro_numero_schede_scadenzario', $request->request->get('filtro_numero_schede_scadenzario'));
            }
        }

        //calcolo tabella
        $schedaPais = null;

        if ($session->get('filtro_numero_schede_scadenzario') == null)
            $schedePerPagina = 10;
        else
            $schedePerPagina = $session->get('filtro_numero_schede_scadenzario');

        $offset = $schedePerPagina * $page - $schedePerPagina;

        //calcolo pagine per paginatore
        $totaleSchede = $schedaPAIRepository->contaSchedeScadenzario($roles[0], $idUser, null);
        $pagineTotali = ceil($totaleSchede / $schedePerPagina);

        if ($pagineTotali == 0)
            $pagineTotali = 1;


        if (in_array("ROLE_ADMIN", $roles)) {

            $schedaPais = $schedaPAIRepository->findBy(['currentPlace' => ['nuova','attiva','approvata','verifica','in_attesa_di_chiusura', 'in_attesa_di_chiusura_con_rinnovo']], array('id' => 'DESC'), $schedePerPagina, $offset);
        
        } else {
            $principale = $schedaPAIRepository->findOperatorePrincipaleScadenzario($idUser);
            $secondarioInf = $schedaPAIRepository->findOperatoreSecondarioInfScadenzario($idUser);
            $secondarioTdr = $schedaPAIRepository->findOperatoreSecondarioTdrScadenzario($idUser);
            $secondarioLog = $schedaPAIRepository->findOperatoreSecondarioLogScadenzario($idUser);
            $secondarioAsa = $schedaPAIRepository->findOperatoreSecondarioAsaScadenzario($idUser);
            $secondarioOss = $schedaPAIRepository->findOperatoreSecondarioOssScadenzario($idUser);
            //unisco gli array e tolgo duplicati
            $schedaPais1 = array_unique (array_merge ($principale, $secondarioInf, $secondarioTdr, $secondarioLog, $secondarioAsa, $secondarioOss));
            //ordino per id in ordine decrescente per avere le schede più recenti in alto
            usort($schedaPais1, fn($a, $b) => $a->getId()-$b->getId());
            $schedaPais1 = array_reverse($schedaPais1);
            $schedaPais = [];
            //costruisco l'elenco schede in base al filtro e alla pagina
            for($i=(($page - 1) * $schedePerPagina); $i<$schedePerPagina*$page; $i++){
                if($i<count($schedaPais1))
                    array_push($schedaPais,$schedaPais1[$i]);     
            }
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
        } elseif ($alertSincronizzazione == 'errore') {
            $this->addFlash(
                'Fallimento',
                'Sincronizzazione fallita!'
            );
        } elseif ($alertSincronizzazione == 'chiusuraFallita') {
            $this->addFlash(
                'Fallimento',
                'Chiusura Fallita! Per chiudere una scheda è necessario aver compilato tutte le
                 scale di valutazione necessarie, la chisura servizio e almeno una valutazione professionale 
                 per operatore coinvolto'
            );
        } elseif ($alertSincronizzazione == 'chiusuraCompletata') {
            $this->addFlash(
                'Successo',
                'Chiusura Completata con successo!'
            );
        } elseif ($alertSincronizzazione == 'approvazioneFallita') {
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
            'pagine_totali' => $pagineTotali,
            'schede_per_pagina' => $schedePerPagina,
            'user' => $user,
            'assistiti' => $assistitiRepository->findAll(),
            'pathName' => $pathName,
            'filtroColoriScadenzario' => $this->filtroColoriScadenzario,
            'filtroNomiStatiScadenzario' => $this->filtroNomiStatiScadenzario,
            'filtroDropdownScadenzario' => $this->filtroDropdownScadenzario,
            'filtroSimboloValutazioneScadenzario' => $this->filtroSimboloValutazioneScadenzario
        ]);
    }
}
