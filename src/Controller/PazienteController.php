<?php

namespace App\Controller;

use App\Entity\Paziente;
use App\Repository\PazienteRepository;
use App\Twig\FiltroSimboloPresenzaPrivacy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/paziente')]
class PazienteController extends AbstractController
{
    private $params;
    private $filtroSimboloPresenzaPrivacy;

    public function __construct(ParameterBagInterface $params, FiltroSimboloPresenzaPrivacy $filtroSimboloPresenzaPrivacy)
    {
        $this->params = $params;
        $this->filtroSimboloPresenzaPrivacy = $filtroSimboloPresenzaPrivacy;
    }

    #[Route('/{page}', name: 'app_paziente_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, PazienteRepository $pazienteRepository, int $page = 1): Response
    {
        //sessione
        $session = $request->getSession();

        $attivazione = $this->params->get('app.audio_privacy_abilitati');

        //preparazione filtri
        $numeroPazientiVisibiliPerPagina = $request->request->get('filtro_numero_pazienti');
        $presenzaPrivacy = $request->request->get('filtro_presenza_privacy');

        if($request->request->get('filtro_ricerca') != null && $request->request->get('filtro_ricerca') != ""){
            $session->set('filtro_ricerca', $request->request->get('filtro_ricerca'));
        }
        else{
            $session->set('filtro_ricerca', null);
        }

        if ($numeroPazientiVisibiliPerPagina == null)
            $pazientiPerPagina = 10;
        else
            $pazientiPerPagina = $numeroPazientiVisibiliPerPagina;

        $offset = $pazientiPerPagina * $page - $pazientiPerPagina;

        //utilizzo filtri

        if($presenzaPrivacy == null || $presenzaPrivacy == 'Tutti'){
            $pazienti = $pazienteRepository->findBy([], array('id' => 'DESC'), $pazientiPerPagina, $offset);
        }
        else if($presenzaPrivacy == 'Si'){
            $pazienti = [];
            $pazienti1 = [];
            $pazientiTotali = $pazienteRepository->findBy([], array('id' => 'DESC'));
            foreach($pazientiTotali as $paziente){
                if($paziente->getAudioPrivacy() != null){
                    array_push($pazienti1, $paziente);
                }
            }
            usort($pazienti1, fn($a, $b) => $a->getId()-$b->getId());
            $pazienti1 = array_reverse($pazienti1);
            for($i=(($page - 1) * $pazientiPerPagina); $i<$pazientiPerPagina*$page; $i++){
                if($i<count($pazienti1))
                    array_push($pazienti,$pazienti1[$i]);     
            }
        }
        else{
            $pazienti = [];
            $pazienti1 = [];
            $pazientiTotali = $pazienteRepository->findBy([], array('id' => 'DESC'));
            foreach($pazientiTotali as $paziente){
                if($paziente->getAudioPrivacy() == null){
                    array_push($pazienti1, $paziente);
                }
            }
            usort($pazienti1, fn($a, $b) => $a->getId()-$b->getId());
            $pazienti1 = array_reverse($pazienti1);
            for($i=(($page - 1) * $pazientiPerPagina); $i<$pazientiPerPagina*$page; $i++){
                if($i<count($pazienti1))
                    array_push($pazienti,$pazienti1[$i]);     
            }
        }

        //utilizzo sistema di ricerca
        if($session->get('filtro_ricerca') != null && $session->get('filtro_ricerca') != ""){
            $pazientiTrovati1 = $pazienteRepository->findByBarraRicerca($session->get('filtro_ricerca'));
            $pazienti = [];
            //costruisco l'elenco schede in base al filtro e alla pagina
             for($i=(($page - 1) * $pazientiPerPagina); $i<$pazientiPerPagina*$page; $i++){
                if($i<count($pazientiTrovati1))
                    array_push($pazienti,$pazientiTrovati1[$i]);     
            }
        }

        //calcolo pagine per paginatore
        //senza ricerca nella barra
        if($session->get('filtro_ricerca') == null && $session->get('filtro_ricerca') == ""){
            $totalePazienti = $pazienteRepository->contaPazienti();
            $pagineTotali = ceil($totalePazienti / $pazientiPerPagina);
        }
        //con la ricerca della barra
        else{
            $totalePazienti = $pazienteRepository->contaPazientiInRicerca($session->get('filtro_ricerca'));
            $pagineTotali = ceil($totalePazienti / $pazientiPerPagina);
        }
        

        if ($pagineTotali == 0)
            $pagineTotali = 1;

        return $this->render('paziente/index.html.twig', [
            'pazientes' => $pazienti,
            'pagina' => $page,
            'ricerca' => $session->get('filtro_ricerca'),
            'pagine_totali' => $pagineTotali,
            'pazienti_per_pagina' => $pazientiPerPagina,
            'presenza_privacy' => $presenzaPrivacy,
            'attivazione' => $attivazione,
            'filtroSimboloPresenzaPrivacy' => $this->filtroSimboloPresenzaPrivacy,
        ]);
    }

    #[Route('/show/{id}', name: 'app_paziente_show', methods: ['GET'])]
    public function show(Paziente $paziente): Response
    {
        return $this->render('paziente/show.html.twig', [
            'paziente' => $paziente,
        ]);
    }
}
