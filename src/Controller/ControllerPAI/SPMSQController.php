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

    #[Route('/delete/{id}', name: 'app_s_p_m_s_q_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, SPMSQ $spmsq, SPMSQRepository $spmsqRepository): Response
    {
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $spmsq->getSchedaPAI());

        if ($request->getMethod() == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $spmsq->getId(), $request->request->get('_token'))) {
                $spmsqRepository->remove($spmsq, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $spmsq->getId(), $request->query->get('_token'))) {
                $spmsqRepository->remove($spmsq, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/new', name: 'app_s_p_m_s_q_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->find($request->query->get('id_pai'));

        $this->denyAccessUnlessGranted('crea_spmsq', $schedaPai);

        $spmsq = new SPMSQ();
        $form = $this->createForm(SPMSQFormType::class, $spmsq);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $spmsq->setOperatore($this->getUser());
            $schedaPai->addIdSpmsq($spmsq);
            $spmsqRepository = $this->entityManager->getRepository(SPMSQ::class);
            $spmsqRepository->add($spmsq, true);
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('spmsq/new.html.twig', [
            's_p_m_s_q' => $spmsq,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{id}/edit', name: 'app_s_p_m_s_q_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SPMSQ $spmsq, SPMSQRepository $spmsqRepository): Response
    {
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $spmsq->getSchedaPAI());

        $form = $this->createForm(SPMSQFormType::class, $spmsq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $spmsqRepository->add($spmsq, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('spmsq/edit.html.twig', [
            's_p_m_s_q' => $spmsq,
            'form' => $form,
        ]);
    }
}
