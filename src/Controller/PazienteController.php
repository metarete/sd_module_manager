<?php

namespace App\Controller;

use App\Entity\Paziente;
use App\Repository\PazienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/paziente')]
class PazienteController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/{page}', name: 'app_paziente_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, PazienteRepository $pazienteRepository, int $page = 1): Response
    {
        $attivazione = $this->params->get('app.audio_privacy_abilitati');

        $numeroPazientiVisibiliPerPagina = $request->request->get('filtro_numero_pazienti');
        if ($numeroPazientiVisibiliPerPagina == null)
            $pazientiPerPagina = 10;
        else
            $pazientiPerPagina = $numeroPazientiVisibiliPerPagina;

        $offset = $pazientiPerPagina * $page - $pazientiPerPagina;


        $pazienti = $pazienteRepository->findBy([], array('id' => 'DESC'), $pazientiPerPagina, $offset);

        //calcolo pagine per paginatore
        $totalePazienti = $pazienteRepository->contaPazienti();
        $pagineTotali = ceil($totalePazienti / $pazientiPerPagina);
        

        if ($pagineTotali == 0)
            $pagineTotali = 1;

        return $this->render('paziente/index.html.twig', [
            'pazientes' => $pazienti,
            'pagina' => $page,
            'pagine_totali' => $pagineTotali,
            'pazienti_per_pagina' => $pazientiPerPagina,
            'attivazione' => $attivazione
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
