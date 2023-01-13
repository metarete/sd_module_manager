<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\AltraTipologiaAssistenza;
use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\EntityPAI\ValutazioneGenerale;
use App\Form\FormPAI\ValutazioneGeneraleFormType;
use App\Repository\ValutazioneGeneraleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

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
    #[Route('/{page}', name: 'app_valutazione_generale_index',requirements: ['page' => '\d+'], methods: ['GET'])]
    public function index(ValutazioneGeneraleRepository $valutazioneGeneraleRepository, int $page=1): Response
    {
        $schedePerPagina = 10;
        $offset = $schedePerPagina*$page-$schedePerPagina;
        $totaleSchede = $valutazioneGeneraleRepository->contaSchede();
        $pagineTotali = ceil($totaleSchede/$schedePerPagina);
        return $this->render('valutazione_generale/index.html.twig', [
            'valutazione_generales' => $valutazioneGeneraleRepository->findBy([], null, $schedePerPagina, $offset ),
            'pagina'=>$page,
            'pagine_totali'=>$pagineTotali
        ]);
    }

    #[Route('/{pathName}/new', name: 'app_valutazione_generale_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $valutazioneGenerale = new ValutazioneGenerale();
        $form = $this->createForm(ValutazioneGeneraleFormType::class, $valutazioneGenerale);
        $form->handleRequest($request);
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $schedaPai->setIdValutazioneGenerale($valutazioneGenerale);
            $valutazioneGeneraleRepository = $this->entityManager->getRepository(ValutazioneGenerale::class);
            $valutazioneGeneraleRepository->add($valutazioneGenerale, true);
            if($this->workflow->can($schedaPai, 'attiva')){
                $this->workflow->apply($schedaPai, 'attiva');
            }
            $this->entityManager->flush();

            if($pathName == 'app_scadenzario_index'){
                return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
            }
            else
                return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('valutazione_generale/new.html.twig', [
            'valutazione_generale' => $valutazioneGenerale,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_valutazione_generale_show', methods: ['GET'])]
    public function show(ValutazioneGenerale $valutazioneGenerale): Response
    {
        $variabileTest = null;
        return $this->render('valutazione_generale/show.html.twig', [
            'valutazione_generale' => $valutazioneGenerale,
            'variabileTest' => $variabileTest
        ]);
    }

    #[Route('/{id}/edit', name: 'app_valutazione_generale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ValutazioneGenerale $valutazioneGenerale, ValutazioneGeneraleRepository $valutazioneGeneraleRepository): Response
    {
        $form = $this->createForm(ValutazioneGeneraleFormType::class, $valutazioneGenerale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $valutazioneGeneraleRepository->add($valutazioneGenerale, true);

            return $this->redirectToRoute('app_valutazione_generale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('valutazione_generale/edit.html.twig', [
            'valutazione_generale' => $valutazioneGenerale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_valutazione_generale_delete', methods: ['POST'])]
    public function delete(Request $request, ValutazioneGenerale $valutazioneGenerale, ValutazioneGeneraleRepository $valutazioneGeneraleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $valutazioneGenerale->getId(), $request->request->get('_token'))) {
            $valutazioneGeneraleRepository->remove($valutazioneGenerale, true);
        }

        return $this->redirectToRoute('app_valutazione_generale_index', [], Response::HTTP_SEE_OTHER);
    }
}
