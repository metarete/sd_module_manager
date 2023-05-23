<?php

namespace App\Controller;

use App\Repository\DiagnosiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/test')]
class ControllerTest extends AbstractController
{
    #[Route('/', name: 'app_test_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {   
        $listaDiagnosi = [];
        return $this->render('test/index.html.twig', [
            'listaDiagnosi' => $listaDiagnosi
        ]);
    }

    #[Route('/search', name: 'app_test_search', methods: ['POST'])]
    public function search(Request $request,DiagnosiRepository $diagnosiRepository): Response
    {
        $input = $request->request->get('search');
        //query che filtra le diagnosi per l'input stringa. Almeno 3 lettere per eseguire la ricerca

        if(strlen($input)>= 3)
            $listaDiagnosi = $diagnosiRepository->findBySerchBar($input);
        else
            $listaDiagnosi=[];

        return $this->render('test/lista.html.twig', [
            'listaDiagnosi' => $listaDiagnosi
        ]);
    }

    
}