<?php

namespace App\Controller;

use App\Entity\TipiAdiweb;
use App\Form\TipiAdiwebType;
use App\Repository\TipiAdiwebRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/superadmin/tipi_adiweb')]
class TipiAdiwebController extends AbstractController
{
    #[Route('/{page}', name: 'app_tipi_adiweb_index',requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, TipiAdiwebRepository $tipiAdiwebRepository, int $page = 1): Response
    {
        $tipi_adiwebs = null;
        $numeroTipiVisibiliPerPagina = $request->request->get('filtro_numero_tipi');
        if ($numeroTipiVisibiliPerPagina == null)
            $tipiPerPagina = 10;
        else
        $tipiPerPagina = $numeroTipiVisibiliPerPagina;

        $offset = $tipiPerPagina * $page - $tipiPerPagina;


        $tipi_adiwebs = $tipiAdiwebRepository->findBy([], array('id' => 'DESC'), $tipiPerPagina, $offset);

         //calcolo pagine per paginatore
         $totaleTipi = $tipiAdiwebRepository->contaTipi();
         $pagineTotali = ceil($totaleTipi / $tipiPerPagina);
         
 
         if ($pagineTotali == 0)
             $pagineTotali = 1;

        return $this->render('tipi_adiweb/index.html.twig', [
            'tipi_adiwebs' => $tipi_adiwebs,
            'pagina' => $page,
            'pagine_totali' => $pagineTotali,
            'tipi_per_pagina' => $tipiPerPagina,
        ]);
    }

    #[Route('/new', name: 'app_tipi_adiweb_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tipiAdiweb = new TipiAdiweb();
        $form = $this->createForm(TipiAdiwebType::class, $tipiAdiweb);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tipiAdiweb);
            $entityManager->flush();

            return $this->redirectToRoute('app_tipi_adiweb_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipi_adiweb/new.html.twig', [
            'tipi_adiweb' => $tipiAdiweb,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_tipi_adiweb_show', methods: ['GET'])]
    public function show(TipiAdiweb $tipiAdiweb): Response
    {
        return $this->render('tipi_adiweb/show.html.twig', [
            'tipi_adiweb' => $tipiAdiweb,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tipi_adiweb_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TipiAdiweb $tipiAdiweb, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TipiAdiwebType::class, $tipiAdiweb);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tipi_adiweb_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tipi_adiweb/edit.html.twig', [
            'tipi_adiweb' => $tipiAdiweb,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_tipi_adiweb_delete', methods: ['GET','POST'])]
    public function delete(Request $request, TipiAdiweb $tipiAdiweb, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipiAdiweb->getId(), $request->query->get('_token'))) {
            $entityManager->remove($tipiAdiweb);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tipi_adiweb_index', [], Response::HTTP_SEE_OTHER);
    }
}
