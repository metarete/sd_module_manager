<?php

namespace App\Controller;

use App\Entity\Obiettivi;
use App\Repository\ObiettiviRepository;
use App\Form\ObiettiviFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/obiettivi')]
class ObiettiviController extends AbstractController
{
    #[Route('/{page}', name: 'app_obiettivi_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, ObiettiviRepository $obiettiviRepository, int $page = 1): Response
    {
        $obiettivis = null;
        $numeroObiettiviVisibiliPerPagina = $request->request->get('filtro_numero_obiettivi');
        if ($numeroObiettiviVisibiliPerPagina == null)
            $obiettiviPerPagina = 10;
        else
            $obiettiviPerPagina = $numeroObiettiviVisibiliPerPagina;

        $offset = $obiettiviPerPagina * $page - $obiettiviPerPagina;


        $obiettivis = $obiettiviRepository->findBy([], array('id' => 'DESC'), $obiettiviPerPagina, $offset);

         //calcolo pagine per paginatore
         $totaleObiettivi = $obiettiviRepository->contaObiettivi();
         $pagineTotali = ceil($totaleObiettivi / $obiettiviPerPagina);
         
 
         if ($pagineTotali == 0)
             $pagineTotali = 1;

        return $this->render('obiettivi/index.html.twig', [
            'obiettivis' => $obiettivis,
            'pagina' => $page,
            'pagine_totali' => $pagineTotali,
            'obiettivi_per_pagina' => $obiettiviPerPagina,
        ]);
    }

    #[Route('/show/{id}', name: 'app_obiettivi_show', methods: ['GET'])]
    public function show(Obiettivi $obiettivi): Response
    {
        return $this->render('obiettivi/show.html.twig', [
            'obiettivi' => $obiettivi,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_obiettivi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Obiettivi $obiettivi, ObiettiviRepository $obiettiviRepository): Response
    {
        $form = $this->createForm(ObiettiviFormType::class, $obiettivi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $obiettiviRepository->add($obiettivi, true);
            return $this->redirectToRoute('app_obiettivi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('obiettivi/edit.html.twig', [
            'obiettivi' => $obiettivi,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_obiettivi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ObiettiviRepository $obiettiviRepository): Response
    {

        $obiettivi = new Obiettivi();
        $form = $this->createForm(ObiettiviFormType::class, $obiettivi);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $obiettivi->setStato(true);
            $obiettiviRepository->add($obiettivi, true);

            return $this->redirectToRoute('app_obiettivi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('obiettivi/new.html.twig', [
            'obiettivi' => $obiettivi,
            'form' => $form,
        ]);
    }

    #[Route('/disattiva/{id}', name: 'app_obiettivi_disattiva', methods: ['GET', 'POST'])]
    public function disattiva(Request $request,Obiettivi $obiettivi , ObiettiviRepository $obiettiviRepository): Response
    {
        
        $obiettivi->setStato(false);
        $obiettiviRepository->add($obiettivi, true);

        return $this->redirectToRoute('app_obiettivi_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/attiva/{id}', name: 'app_obiettivi_attiva', methods: ['GET', 'POST'])]
    public function attiva(Request $request,Obiettivi $obiettivi , ObiettiviRepository $obiettiviRepository): Response
    {
        
        $obiettivi->setStato(true);
        $obiettiviRepository->add($obiettivi, true);

        return $this->redirectToRoute('app_obiettivi_index', [], Response::HTTP_SEE_OTHER);
    }
}