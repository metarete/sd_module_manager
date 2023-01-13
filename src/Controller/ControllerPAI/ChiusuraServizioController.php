<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\EntityPAI\ChiusuraServizio;
use Symfony\Component\HttpFoundation\Request;
use App\Form\FormPAI\ChiusuraServizioFormType;
use App\Repository\ChiusuraServizioRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/chiusura_servizio')]
class ChiusuraServizioController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }
    #[Route('/{page}', name: 'app_chiusura_servizio_index',requirements: ['page' => '\d+'], methods: ['GET'])]
    public function index(ChiusuraServizioRepository $chiusuraServizioRepository, int $page=1): Response
    {
        $schedePerPagina = 10;
        $offset = $schedePerPagina*$page-$schedePerPagina;
        $totaleSchede = $chiusuraServizioRepository->contaSchede();
        $pagineTotali = ceil($totaleSchede/$schedePerPagina);
        return $this->render('chiusura_servizio/index.html.twig', [
            'chiusura_servizios' => $chiusuraServizioRepository->findBy([], null, $schedePerPagina, $offset ),
            'pagina'=>$page,
            'pagine_totali'=>$pagineTotali
        ]);
    }

    #[Route('/{pathName}/new', name: 'app_chiusura_servizio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $chiusuraServizio = new ChiusuraServizio();
        $form = $this->createForm(ChiusuraServizioFormType::class, $chiusuraServizio);
        $form->handleRequest($request);
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $schedaPai->setIdChiusuraServizio($chiusuraServizio);
            $chiusuraServizioRepository = $this->entityManager->getRepository(ChiusuraServizio::class);
            $chiusuraServizioRepository->add($chiusuraServizio, true);
            $this->entityManager->flush();

            if($pathName == 'app_scadenzario_index'){
                return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
            }
            else
                return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chiusura_servizio/new.html.twig', [
            'chiusura_servizio' => $chiusuraServizio,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_chiusura_servizio_show', methods: ['GET'])]
    public function show(ChiusuraServizio $chiusuraServizio): Response
    {
        $variabileTest = null;
        return $this->render('chiusura_servizio/show.html.twig', [
            'chiusura_servizio' => $chiusuraServizio,
            'variabileTest' => $variabileTest
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chiusura_servizio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ChiusuraServizio $chiusuraServizio, ChiusuraServizioRepository $chiusuraServizioRepository): Response
    {
        $form = $this->createForm(ChiusuraServizioFormType::class, $chiusuraServizio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chiusuraServizioRepository->add($chiusuraServizio, true);
            

            return $this->redirectToRoute('app_chiusura_servizio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chiusura_servizio/edit.html.twig', [
            'chiusura_servizio' => $chiusuraServizio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chiusura_servizio_delete', methods: ['POST'])]
    public function delete(Request $request, ChiusuraServizio $chiusuraServizio, ChiusuraServizioRepository $chiusuraServizioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chiusuraServizio->getId(), $request->request->get('_token'))) {
            $chiusuraServizioRepository->remove($chiusuraServizio, true);
        }

        return $this->redirectToRoute('app_chiusura_servizio_index', [], Response::HTTP_SEE_OTHER);
    }
}
