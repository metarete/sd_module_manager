<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Lesioni;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\LesioniFormType;
use App\Repository\LesioniRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/lesioni')]
class LesioniController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }

    #[Route('/delete/{id}', name: 'app_lesioni_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Lesioni $lesioni, LesioniRepository $lesioniRepository): Response
    {
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $lesioni->getSchedaPAI());

        if ($request->getMethod() == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $lesioni->getId(), $request->request->get('_token'))) {
                $lesioniRepository->remove($lesioni, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $lesioni->getId(), $request->query->get('_token'))) {
                $lesioniRepository->remove($lesioni, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    

    #[Route('/{pathName}/new', name: 'app_lesioni_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->find($request->query->get('id_pai'));

        $this->denyAccessUnlessGranted('crea_lesioni', $schedaPai);


        $lesioni = new Lesioni();
        $form = $this->createForm(LesioniFormType::class, $lesioni);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $lesioni->setOperatore($this->getUser());
            $schedaPai->addIdLesioni($lesioni);
            $lesioniRepository = $this->entityManager->getRepository(Lesioni::class);
            $lesioniRepository->add($lesioni, true);
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPai->getId()], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPai->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lesioni/new.html.twig', [
            'lesioni' => $lesioni,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lesioni_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lesioni $lesioni, LesioniRepository $lesioniRepository): Response
    {
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $lesioni->getSchedaPAI());

        $form = $this->createForm(LesioniFormType::class, $lesioni);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lesioniRepository->add($lesioni, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lesioni/edit.html.twig', [
            'lesioni' => $lesioni,
            'form' => $form,
        ]);
    }
}
