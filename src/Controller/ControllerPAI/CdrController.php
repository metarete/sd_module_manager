<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Cdr;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\CdrFormType;
use App\Repository\CdrRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cdr')]
class CdrController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }

    #[Route('/delete/{id}', name: 'app_cdr_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Cdr $cdr, CdrRepository $cdrRepository): Response
    {
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $cdr->getSchedaPAI());

        if ($request->getMethod() == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $cdr->getId(), $request->request->get('_token'))) {
                $cdrRepository->remove($cdr, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $cdr->getId(), $request->query->get('_token'))) {
                $cdrRepository->remove($cdr, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    

    #[Route('/{pathName}/new', name: 'app_cdr_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->find($request->query->get('id_pai'));

        $this->denyAccessUnlessGranted('crea_cdr', $schedaPai);

        $cdr = new Cdr();
        $form = $this->createForm(CdrFormType::class, $cdr);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $cdr->setAutoreCdr($this->getUser());
            $schedaPai->addCdr($cdr);
            $cdrRepository = $this->entityManager->getRepository(Cdr::class);
            $cdrRepository->add($cdr, true);
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cdr/new.html.twig', [
            'cdr' => $cdr,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cdr_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cdr $cdr, CdrRepository $cdrRepository): Response
    {
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $cdr->getSchedaPAI());

        $form = $this->createForm(CdrFormType::class, $cdr);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cdrRepository->add($cdr, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cdr/edit.html.twig', [
            'cdr' => $cdr,
            'form' => $form,
        ]);
    }
}
