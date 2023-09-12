<?php

namespace App\Controller;

use App\Entity\Diagnosi;
use App\Form\DiagnosiType;
use App\Repository\DiagnosiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/diagnosi')]
class DiagnosiController extends AbstractController
{
    #[Route('/delete/{id}', name: 'app_diagnosi_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Diagnosi $diagnosi, DiagnosiRepository $diagnosiRepository ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diagnosi->getId(), $request->query->get('_token'))) {
            $diagnosiRepository->remove($diagnosi, true);
        }

        return $this->redirectToRoute('app_diagnosi_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{page}', name: 'app_diagnosi_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, DiagnosiRepository $diagnosiRepository, int $page = 1): Response
    {
        $numeroDiagnosiVisibiliPerPagina = $request->request->get('filtro_numero_diagnosi');

        if ($numeroDiagnosiVisibiliPerPagina == null)
            $diagnosiPerPagina = 10;
        else
            $diagnosiPerPagina = $numeroDiagnosiVisibiliPerPagina;

        $offset = $diagnosiPerPagina * $page - $diagnosiPerPagina;

        $diagnosi = $diagnosiRepository->findBy([], array('id' => 'DESC'), $diagnosiPerPagina, $offset);

        //calcolo pagine per paginatore
        $totaleDiagnosi = $diagnosiRepository->contaDiagnosi();
        $pagineTotali = ceil($totaleDiagnosi / $diagnosiPerPagina);
         
 
        if ($pagineTotali == 0)
            $pagineTotali = 1;

        return $this->render('diagnosi/index.html.twig', [
            'diagnosis' => $diagnosi,
            'pagina' => $page,
            'pagine_totali' => $pagineTotali,
            'diagnosi_per_pagina' => $diagnosiPerPagina,
        ]);
    }

    #[Route('/new', name: 'app_diagnosi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DiagnosiRepository $diagnosiRepository): Response
    {
        $diagnosi = new Diagnosi();
        $form = $this->createForm(DiagnosiType::class, $diagnosi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diagnosiRepository->add($diagnosi, true);

            return $this->redirectToRoute('app_diagnosi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('diagnosi/new.html.twig', [
            'diagnosi' => $diagnosi,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_diagnosi_show', methods: ['GET'])]
    public function show(Diagnosi $diagnosi): Response
    {
        return $this->render('diagnosi/show.html.twig', [
            'diagnosi' => $diagnosi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_diagnosi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Diagnosi $diagnosi, DiagnosiRepository $diagnosiRepository): Response
    {
        $form = $this->createForm(DiagnosiType::class, $diagnosi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diagnosiRepository->add($diagnosi, true);

            return $this->redirectToRoute('app_diagnosi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('diagnosi/edit.html.twig', [
            'diagnosi' => $diagnosi,
            'form' => $form,
        ]);
    }
}
