<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Barthel;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\BarthelFormType;
use App\Repository\BarthelRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/barthel')]
class BarthelController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }

    #[Route('/delete/{id}', name: 'app_barthel_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Barthel $barthel, BarthelRepository $barthelRepository): Response
    {
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $barthel->getSchedaPAI());

        if ($request->getMethod() == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $barthel->getId(), $request->request->get('_token'))) {
                $barthelRepository->remove($barthel, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $barthel->getId(), $request->query->get('_token'))) {
                $barthelRepository->remove($barthel, true);
            }
        }


        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/new', name: 'app_barthel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->find($request->query->get('id_pai'));

        $this->denyAccessUnlessGranted('crea_barthel', $schedaPai);

        $barthel = new Barthel();
        $form = $this->createForm(BarthelFormType::class, $barthel);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $barthel->setOperatore($this->getUser());
            $schedaPai->addIdBarthel($barthel);
            $barthelRepository = $this->entityManager->getRepository(Barthel::class);
            $barthelRepository->add($barthel, true);
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('barthel/new.html.twig', [
            'barthel' => $barthel,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{id}/edit', name: 'app_barthel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Barthel $barthel, BarthelRepository $barthelRepository): Response
    {
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $barthel->getSchedaPAI());

        $form = $this->createForm(BarthelFormType::class, $barthel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $barthelRepository->add($barthel, true);
            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('barthel/edit.html.twig', [
            'barthel' => $barthel,
            'form' => $form,
        ]);
    }
}
