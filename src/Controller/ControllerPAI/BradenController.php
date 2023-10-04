<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Braden;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\BradenFormType;
use App\Repository\BradenRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/braden')]
class BradenController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }

    #[Route('/delete/{id}', name: 'app_form_pai_braden_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Braden $braden, BradenRepository $bradenRepository): Response
    {
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $braden->getSchedaPAI());

        if ($request->getMethod() == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $braden->getId(), $request->request->get('_token'))) {
                $bradenRepository->remove($braden, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $braden->getId(), $request->query->get('_token'))) {
                $bradenRepository->remove($braden, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    

    #[Route('/{pathName}/new', name: 'app_form_pai_braden_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($request->query->get('id_pai'));

        $this->denyAccessUnlessGranted('crea_braden', $schedaPai);

        $braden = new Braden();
        $form = $this->createForm(BradenFormType::class, $braden);
        $form->handleRequest($request);
       
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $braden->setOperatore($this->getUser());
            $schedaPai->addIdBraden($braden);
            $bradenRepository = $this->entityManager->getRepository(Braden::class);
            $bradenRepository->add($braden, true);
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPai->getId()], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPai->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('braden/new.html.twig', [
            'braden' => $braden,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{id}/edit', name: 'app_form_pai_braden_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Braden $braden, BradenRepository $bradenRepository): Response
    {
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $braden->getSchedaPAI());

        $form = $this->createForm(BradenFormType::class, $braden);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bradenRepository->add($braden, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('braden/edit.html.twig', [
            'braden' => $braden,
            'form' => $form,
        ]);
    }
}
