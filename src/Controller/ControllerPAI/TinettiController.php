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
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }

    #[Route('/delete/{id}', name: 'app_tinetti_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Tinetti $tinetti, TinettiRepository $tinettiRepository): Response
    {
        $post = $tinetti->getSchedaPAI();
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $post);

        $metodo = $request->getMethod();
        if ($metodo == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $tinetti->getId(), $request->request->get('_token'))) {
                $tinettiRepository->remove($tinetti, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $tinetti->getId(), $request->query->get('_token'))) {
                $tinettiRepository->remove($tinetti, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    

    #[Route('/{pathName}/new', name: 'app_tinetti_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $id_pai = $request->query->get('id_pai');
        $page = $request->query->get('page');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);

        $post = $schedaPai;
        $this->denyAccessUnlessGranted('crea_tinetti', $post);


        $tinetti = new Tinetti();
        $form = $this->createForm(TinettiFormType::class, $tinetti);
        $form->handleRequest($request);
       
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $tinetti->setOperatore($user);
            $schedaPai->addIdTinetti($tinetti);
            $tinettiRepository = $this->entityManager->getRepository(Tinetti::class);
            $tinettiRepository->add($tinetti, true);
            $this->entityManager->flush();


            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $page], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $page], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tinetti/new.html.twig', [
            'tinetti' => $tinetti,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    /*#[Route('/show/{id}', name: 'app_tinetti_show', methods: ['GET'])]
    public function show(Tinetti $tinetti): Response
    {
        $variabileTest = null;
        return $this->render('tinetti/show.html.twig', [
            'tinetti' => $tinetti,
            'variabileTest' => $variabileTest
        ]);
    }*/

    #[Route('/{id}/edit', name: 'app_tinetti_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tinetti $tinetti, TinettiRepository $tinettiRepository): Response
    {
        $post = $tinetti->getSchedaPAI();
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $post);

        $form = $this->createForm(TinettiFormType::class, $tinetti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tinettiRepository->add($tinetti, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tinetti/edit.html.twig', [
            'tinetti' => $tinetti,
            'form' => $form,
        ]);
    }
}
