<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\AltraTipologiaAssistenza;
use App\Form\EntityPAI\AltraTipologiaAssistenzaType;
use App\Repository\AltraTipologiaAssistenzaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/altra_tipologia_assistenza')]
class AltraTipologiaAssistenzaController extends AbstractController
{
    #[Route('/', name: 'app_controller_pai_altra_tipologia_assistenza_index', methods: ['GET'])]
    public function index(AltraTipologiaAssistenzaRepository $altraTipologiaAssistenzaRepository): Response
    {
        return $this->render('controller_pai/altra_tipologia_assistenza/index.html.twig', [
            'altra_tipologia_assistenzas' => $altraTipologiaAssistenzaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_controller_pai_altra_tipologia_assistenza_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AltraTipologiaAssistenzaRepository $altraTipologiaAssistenzaRepository): Response
    {
        $altraTipologiaAssistenza = new AltraTipologiaAssistenza();
        $form = $this->createForm(AltraTipologiaAssistenzaType::class, $altraTipologiaAssistenza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $altraTipologiaAssistenzaRepository->add($altraTipologiaAssistenza, true);

            return $this->redirectToRoute('app_controller_pai_altra_tipologia_assistenza_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('controller_pai/altra_tipologia_assistenza/new.html.twig', [
            'altra_tipologia_assistenza' => $altraTipologiaAssistenza,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_controller_pai_altra_tipologia_assistenza_show', methods: ['GET'])]
    public function show(AltraTipologiaAssistenza $altraTipologiaAssistenza): Response
    {
        return $this->render('controller_pai/altra_tipologia_assistenza/show.html.twig', [
            'altra_tipologia_assistenza' => $altraTipologiaAssistenza,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_controller_pai_altra_tipologia_assistenza_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AltraTipologiaAssistenza $altraTipologiaAssistenza, AltraTipologiaAssistenzaRepository $altraTipologiaAssistenzaRepository): Response
    {
        $form = $this->createForm(AltraTipologiaAssistenzaType::class, $altraTipologiaAssistenza);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $altraTipologiaAssistenzaRepository->add($altraTipologiaAssistenza, true);

            return $this->redirectToRoute('app_controller_pai_altra_tipologia_assistenza_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('controller_pai/altra_tipologia_assistenza/edit.html.twig', [
            'altra_tipologia_assistenza' => $altraTipologiaAssistenza,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_controller_pai_altra_tipologia_assistenza_delete', methods: ['POST'])]
    public function delete(Request $request, AltraTipologiaAssistenza $altraTipologiaAssistenza, AltraTipologiaAssistenzaRepository $altraTipologiaAssistenzaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$altraTipologiaAssistenza->getId(), $request->request->get('_token'))) {
            $altraTipologiaAssistenzaRepository->remove($altraTipologiaAssistenza, true);
        }

        return $this->redirectToRoute('app_controller_pai_altra_tipologia_assistenza_index', [], Response::HTTP_SEE_OTHER);
    }
}
