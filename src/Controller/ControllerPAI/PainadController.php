<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Painad;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\PainadFormType;
use App\Repository\PainadRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/painad')]
class PainadController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }

    #[Route('/delete/{id}', name: 'app_painad_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, Painad $painad, PainadRepository $painadRepository): Response
    {
        $post = $painad->getSchedaPAI();
        $this->denyAccessUnlessGranted('elimina_scala_valutazione', $post);

        $metodo = $request->getMethod();
        if ($metodo == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $painad->getId(), $request->request->get('_token'))) {
                $painadRepository->remove($painad, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $painad->getId(), $request->query->get('_token'))) {
                $painadRepository->remove($painad, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }

    

    #[Route('/{pathName}/new', name: 'app_painad_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);

        $post = $schedaPai;
        $this->denyAccessUnlessGranted('crea_painad', $post);


        $painad = new Painad();
        $form = $this->createForm(PainadFormType::class, $painad);
        $form->handleRequest($request);
        
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $painad->setOperatore($user);
            $schedaPai->addIdPainad($painad);
            $painadRepository = $this->entityManager->getRepository(Painad::class);
            $painadRepository->add($painad, true);
            $this->entityManager->flush();


            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('painad/new.html.twig', [
            'painad' => $painad,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/show/{id}', name: 'app_painad_show', methods: ['GET'])]
    public function show(Painad $painad): Response
    {
        $variabileTest = null;
        return $this->render('painad/show.html.twig', [
            'painad' => $painad,
            'variabileTest' => $variabileTest
        ]);
    }

    #[Route('/{id}/edit', name: 'app_painad_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Painad $painad, PainadRepository $painadRepository): Response
    {
        $post = $painad->getSchedaPAI();
        $this->denyAccessUnlessGranted('modifica_scala_valutazione', $post);

        $form = $this->createForm(PainadFormType::class, $painad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $painadRepository->add($painad, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('painad/edit.html.twig', [
            'painad' => $painad,
            'form' => $form,
        ]);
    }
}
