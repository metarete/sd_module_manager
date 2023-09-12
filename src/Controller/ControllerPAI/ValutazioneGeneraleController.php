<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\EntityPAI\ValutazioneGenerale;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FormPAI\ValutazioneGeneraleFormType;
use App\Repository\ValutazioneGeneraleRepository;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/valutazione_generale')]
class ValutazioneGeneraleController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;
    private $workflow;

    public function __construct(ManagerRegistry $managerRegistry, WorkflowInterface $schedePaiCreatingStateMachine)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
        $this->workflow = $schedePaiCreatingStateMachine;
    }

    #[Route('/delete/{id}', name: 'app_valutazione_generale_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, ValutazioneGenerale $valutazioneGenerale, ValutazioneGeneraleRepository $valutazioneGeneraleRepository): Response
    {
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $valutazioneGenerale->getSchedaPAI());

        $schedePaiRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedePaiRepository->findOneBySomeField($valutazioneGenerale->getSchedaPAI()->getId());

        if ($request->getMethod() == "POST") {
            if ($this->isCsrfTokenValid('delete' . $valutazioneGenerale->getId(), $request->request->get('_token'))) {
                if ($this->workflow->can($schedaPai, 'approva_per_cancellazione')) {
                    $schedaPai->setCurrentPlace('approvata');
                }
                $valutazioneGeneraleRepository->remove($valutazioneGenerale, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $valutazioneGenerale->getId(), $request->query->get('_token'))) {
                if ($this->workflow->can($schedaPai, 'approva_per_cancellazione')) {
                    $schedaPai->setCurrentPlace('approvata');
                }
                $valutazioneGeneraleRepository->remove($valutazioneGenerale, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/{pathName}/new', name: 'app_valutazione_generale_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {        
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->find($request->query->get('id_pai'));

        $this->denyAccessUnlessGranted('crea_valutazione_generale', $schedaPai);
        
        $valutazioneGenerale = new ValutazioneGenerale();
        $form = $this->createForm(ValutazioneGeneraleFormType::class, $valutazioneGenerale);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $valutazioneGenerale->setOperatore($this->getUser());
            $schedaPai->setIdValutazioneGenerale($valutazioneGenerale);
            $valutazioneGeneraleRepository = $this->entityManager->getRepository(ValutazioneGenerale::class);
            $valutazioneGeneraleRepository->add($valutazioneGenerale, true);
            if ($this->workflow->can($schedaPai, 'attiva')) {
                $this->workflow->apply($schedaPai, 'attiva');
            }
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        }

      
        
        return $this->renderForm('valutazione_generale/new.html.twig', [
            'valutazione_generale' => $valutazioneGenerale,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{id}/edit', name: 'app_valutazione_generale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ValutazioneGenerale $valutazioneGenerale, ValutazioneGeneraleRepository $valutazioneGeneraleRepository): Response
    {
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $valutazioneGenerale->getSchedaPAI());

        $form = $this->createForm(ValutazioneGeneraleFormType::class, $valutazioneGenerale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $valutazioneGeneraleRepository->add($valutazioneGenerale, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('valutazione_generale/edit.html.twig', [
            'valutazione_generale' => $valutazioneGenerale,
            'form' => $form,
        ]);
    }
}
