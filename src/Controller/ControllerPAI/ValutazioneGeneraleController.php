<?php

namespace App\Controller\ControllerPAI;


use App\Service\BisogniService;
use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\EntityPAI\ValutazioneGenerale;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\AltraTipologiaAssistenzaService;
use App\Form\FormPAI\ValutazioneGeneraleFormType;
use App\Repository\ValutazioneGeneraleRepository;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/valutazione_generale')]
class ValutazioneGeneraleController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;
    private $workflow;
    private $altraTipologiaAssistenzaService;
    private $bisogniService;


    public function __construct(ManagerRegistry $managerRegistry, WorkflowInterface $schedePaiCreatingStateMachine,  AltraTipologiaAssistenzaService $altraTipologiaAssistenzaService, BisogniService $bisogniService)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
        $this->workflow = $schedePaiCreatingStateMachine;
        $this->altraTipologiaAssistenzaService = $altraTipologiaAssistenzaService;
        $this->bisogniService = $bisogniService;
    }
    #[Route('/delete/{id}', name: 'app_valutazione_generale_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, ValutazioneGenerale $valutazioneGenerale, ValutazioneGeneraleRepository $valutazioneGeneraleRepository): Response
    {
        $post = $valutazioneGenerale->getSchedaPAI();
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $post);

        $idSchedaPai = $valutazioneGenerale->getSchedaPAI()->getId();
        $schedePaiRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedePaiRepository->findOneBySomeField($idSchedaPai);
        $metodo = $request->getMethod();
        if ($metodo == "POST") {
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
        $id_pai = $request->query->get('id_pai');
        $page = $request->query->get('page');
        
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);

        $post = $schedaPai;
        $this->denyAccessUnlessGranted('crea_valutazione_generale', $post);
        
        
        $valutazioneGenerale = new ValutazioneGenerale();
        $form = $this->createForm(ValutazioneGeneraleFormType::class, $valutazioneGenerale);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $valutazioneGenerale->setOperatore($user);
            $schedaPai->setIdValutazioneGenerale($valutazioneGenerale);
            $valutazioneGeneraleRepository = $this->entityManager->getRepository(ValutazioneGenerale::class);
            $valutazioneGeneraleRepository->add($valutazioneGenerale, true);
            if ($this->workflow->can($schedaPai, 'attiva')) {
                $this->workflow->apply($schedaPai, 'attiva');
            }
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $page], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $page], Response::HTTP_SEE_OTHER);
        }

      
        
        return $this->renderForm('valutazione_generale/new.html.twig', [
            'valutazione_generale' => $valutazioneGenerale,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/show/{id}', name: 'app_valutazione_generale_show', methods: ['GET'])]
    public function show(ValutazioneGenerale $valutazioneGenerale): Response
    {
        $altraTipologiaAssistenza = [];
        $altraTipologiaAssistenza = $this->altraTipologiaAssistenzaService->getAltreTipologieAssistenza($valutazioneGenerale);
        $bisogni = [];
        $bisogni = $this->bisogniService->getBisogni($valutazioneGenerale);
        $variabileTest = null;
        return $this->render('valutazione_generale/show.html.twig', [
            'valutazione_generale' => $valutazioneGenerale,
            'variabileTest' => $variabileTest,
            'altra_tipologia_assistenza' => $altraTipologiaAssistenza,
            'bisogni' => $bisogni
        ]);
    }

    #[Route('/{id}/edit', name: 'app_valutazione_generale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ValutazioneGenerale $valutazioneGenerale, ValutazioneGeneraleRepository $valutazioneGeneraleRepository): Response
    {
        $post = $valutazioneGenerale->getSchedaPAI();
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $post);

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
