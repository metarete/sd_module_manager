<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Tinetti;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\TinettiFormType;
use App\Repository\TinettiRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tinetti')]
class TinettiController extends AbstractController
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }
    #[Route('/', name: 'app_tinetti_index', methods: ['GET'])]
    public function index(TinettiRepository $tinettiRepository): Response
    {
        return $this->render('tinetti/index.html.twig', [
            'tinettis' => $tinettiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tinetti_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $tinetti = new Tinetti();
        $form = $this->createForm(TinettiFormType::class, $tinetti);
        $form->handleRequest($request);
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $schedaPai->addIdTinetti($tinetti);
            $tinettiRepository = $this->entityManager->getRepository(Tinetti::class);
            $tinettiRepository->add($tinetti, true);
            $this->entityManager->flush();


            return $this->redirectToRoute('app_tinetti_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tinetti/new.html.twig', [
            'tinetti' => $tinetti,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tinetti_show', methods: ['GET'])]
    public function show(Tinetti $tinetti): Response
    {
        return $this->render('tinetti/show.html.twig', [
            'tinetti' => $tinetti,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tinetti_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tinetti $tinetti, TinettiRepository $tinettiRepository): Response
    {
        $form = $this->createForm(TinettiFormType::class, $tinetti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tinettiRepository->add($tinetti, true);

            return $this->redirectToRoute('app_tinetti_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tinetti/edit.html.twig', [
            'tinetti' => $tinetti,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tinetti_delete', methods: ['POST'])]
    public function delete(Request $request, Tinetti $tinetti, TinettiRepository $tinettiRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tinetti->getId(), $request->request->get('_token'))) {
            $tinettiRepository->remove($tinetti, true);
        }

        return $this->redirectToRoute('app_tinetti_index', [], Response::HTTP_SEE_OTHER);
    }
}
