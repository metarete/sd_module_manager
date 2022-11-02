<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\SPMSQ;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\SPMSQFormType;
use App\Repository\SPMSQRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/spmsq')]
class SPMSQController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }
    #[Route('/{page}', name: 'app_s_p_m_s_q_index',requirements: ['page' => '\d+'], methods: ['GET'])]
    public function index(SPMSQRepository $sPMSQRepository, int $page=1): Response
    {
        $schedePerPagina = 10;
        $offset = $schedePerPagina*$page-$schedePerPagina;
        $totaleSchede = $sPMSQRepository->contaSchede();
        $pagineTotali = ceil($totaleSchede/$schedePerPagina);
        return $this->render('spmsq/index.html.twig', [
            's_p_m_s_qs' => $sPMSQRepository->findBy([], null, $schedePerPagina, $offset ),
            'pagina'=>$page,
            'pagine_totali'=>$pagineTotali
        ]);
    }

    #[Route('/new', name: 'app_s_p_m_s_q_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $sPMSQ = new SPMSQ();
        $form = $this->createForm(SPMSQFormType::class, $sPMSQ);
        $form->handleRequest($request);
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $schedaPai->addIdSpmsq($sPMSQ);
            $spmsqRepository = $this->entityManager->getRepository(SPMSQ::class);
            $spmsqRepository->add($sPMSQ, true);
            $this->entityManager->flush();


            return $this->redirectToRoute('app_s_p_m_s_q_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('spmsq/new.html.twig', [
            's_p_m_s_q' => $sPMSQ,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_s_p_m_s_q_show', methods: ['GET'])]
    public function show(SPMSQ $sPMSQ): Response
    {
        $variabileTest = null;
        return $this->render('spmsq/show.html.twig', [
            's_p_m_s_q' => $sPMSQ,
            'variabileTest' => $variabileTest
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_p_m_s_q_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SPMSQ $sPMSQ, SPMSQRepository $sPMSQRepository): Response
    {
        $form = $this->createForm(SPMSQFormType::class, $sPMSQ);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sPMSQRepository->add($sPMSQ, true);

            return $this->redirectToRoute('app_s_p_m_s_q_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('spmsq/edit.html.twig', [
            's_p_m_s_q' => $sPMSQ,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_s_p_m_s_q_delete', methods: ['POST'])]
    public function delete(Request $request, SPMSQ $sPMSQ, SPMSQRepository $sPMSQRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sPMSQ->getId(), $request->request->get('_token'))) {
            $sPMSQRepository->remove($sPMSQ, true);
        }

        return $this->redirectToRoute('app_s_p_m_s_q_index', [], Response::HTTP_SEE_OTHER);
    }
}
