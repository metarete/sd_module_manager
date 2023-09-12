<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Vas;
use App\Form\FormPAI\VasFormType;
use App\Repository\VasRepository;
use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/vas')]
class VasController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }

    #[Route('/delete/{id}', name: 'app_vas_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Vas $vas, VasRepository $vasRepository): Response
    {
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $vas->getSchedaPAI());

        if ($request->getMethod() == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $vas->getId(), $request->request->get('_token'))) {
                $vasRepository->remove($vas, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $vas->getId(), $request->query->get('_token'))) {
                $vasRepository->remove($vas, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/new', name: 'app_vas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->find($request->query->get('id_pai'));

        $this->denyAccessUnlessGranted('crea_vas', $schedaPai);

        $vas = new Vas();
        $form = $this->createForm(VasFormType::class, $vas);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $vas->setOperatore($this->getUser());
            $schedaPai->addIdVas($vas);
            $vasRepository = $this->entityManager->getRepository(Vas::class);
            $vasRepository->add($vas, true);
            $this->entityManager->flush();


            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vas/new.html.twig', [
            'va' => $vas,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vas $vas, VasRepository $vasRepository): Response
    {
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $vas->getSchedaPAI());

        $form = $this->createForm(VasFormType::class, $vas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vasRepository->add($vas, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vas/edit.html.twig', [
            'va' => $vas,
            'form' => $form,
        ]);
    }
}
