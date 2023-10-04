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

    #[Route('/delete/{id}', name: 'app_chiusura_servizio_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, ChiusuraServizio $chiusuraServizio, ChiusuraServizioRepository $chiusuraServizioRepository): Response
    {
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $chiusuraServizio->getSchedaPAI());

        if ($request->getMethod() == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $chiusuraServizio->getId(), $request->request->get('_token'))) {
                $chiusuraServizioRepository->remove($chiusuraServizio, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $chiusuraServizio->getId(), $request->query->get('_token'))) {
                $chiusuraServizioRepository->remove($chiusuraServizio, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    

    #[Route('/{pathName}/new', name: 'app_chiusura_servizio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->find($request->query->get('id_pai'));

        $this->denyAccessUnlessGranted('crea_chiusura_servizio', $schedaPai);

        $chiusuraServizio = new ChiusuraServizio();
        $form = $this->createForm(ChiusuraServizioFormType::class, $chiusuraServizio);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $chiusuraServizio->setOperatore($this->getUser());
            $schedaPai->setIdChiusuraServizio($chiusuraServizio);
            $chiusuraServizioRepository = $this->entityManager->getRepository(ChiusuraServizio::class);
            $chiusuraServizioRepository->add($chiusuraServizio, true);
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPai->getId()], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPai->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chiusura_servizio/new.html.twig', [
            'chiusura_servizio' => $chiusuraServizio,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chiusura_servizio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ChiusuraServizio $chiusuraServizio, ChiusuraServizioRepository $chiusuraServizioRepository): Response
    {
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $chiusuraServizio->getSchedaPAI());

        $form = $this->createForm(ChiusuraServizioFormType::class, $chiusuraServizio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chiusuraServizioRepository->add($chiusuraServizio, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chiusura_servizio/edit.html.twig', [
            'chiusura_servizio' => $chiusuraServizio,
            'form' => $form,
        ]);
    }
}
