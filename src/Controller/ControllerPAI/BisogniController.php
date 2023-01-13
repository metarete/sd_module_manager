<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Bisogni;
use App\Form\EntityPAI\BisogniType;
use App\Repository\BisogniRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bisogni')]
class BisogniController extends AbstractController
{
    #[Route('/', name: 'app_controller_pai_bisogni_index', methods: ['GET'])]
    public function index(BisogniRepository $bisogniRepository): Response
    {
        return $this->render('controller_pai/bisogni/index.html.twig', [
            'bisognis' => $bisogniRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_controller_pai_bisogni_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BisogniRepository $bisogniRepository): Response
    {
        $bisogni = new Bisogni();
        $form = $this->createForm(BisogniType::class, $bisogni);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bisogniRepository->add($bisogni, true);

            return $this->redirectToRoute('app_controller_pai_bisogni_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('controller_pai/bisogni/new.html.twig', [
            'bisogni' => $bisogni,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_controller_pai_bisogni_show', methods: ['GET'])]
    public function show(Bisogni $bisogni): Response
    {
        return $this->render('controller_pai/bisogni/show.html.twig', [
            'bisogni' => $bisogni,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_controller_pai_bisogni_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bisogni $bisogni, BisogniRepository $bisogniRepository): Response
    {
        $form = $this->createForm(BisogniType::class, $bisogni);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bisogniRepository->add($bisogni, true);

            return $this->redirectToRoute('app_controller_pai_bisogni_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('controller_pai/bisogni/edit.html.twig', [
            'bisogni' => $bisogni,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_controller_pai_bisogni_delete', methods: ['POST'])]
    public function delete(Request $request, Bisogni $bisogni, BisogniRepository $bisogniRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bisogni->getId(), $request->request->get('_token'))) {
            $bisogniRepository->remove($bisogni, true);
        }

        return $this->redirectToRoute('app_controller_pai_bisogni_index', [], Response::HTTP_SEE_OTHER);
    }
}
