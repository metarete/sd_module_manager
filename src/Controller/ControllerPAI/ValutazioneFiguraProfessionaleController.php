<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use App\Form\FormPAI\ValutazioneFiguraProfessionaleFormType;
use App\Repository\ValutazioneFiguraProfessionaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/valutazione_figura_professionale')]
class ValutazioneFiguraProfessionaleController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }
    #[Route('/delete/{id}', name: 'app_valutazione_figura_professionale_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, ValutazioneFiguraProfessionale $valutazioneFiguraProfessionale, ValutazioneFiguraProfessionaleRepository $valutazioneFiguraProfessionaleRepository): Response
    {
        $metodo = $request->getMethod();
        if ($metodo == 'POST') {
            if ($this->isCsrfTokenValid('delete' . $valutazioneFiguraProfessionale->getId(), $request->request->get('_token'))) {
                $valutazioneFiguraProfessionaleRepository->remove($valutazioneFiguraProfessionale, true);
            }
        } else {
            if ($this->isCsrfTokenValid('delete' . $valutazioneFiguraProfessionale->getId(), $request->query->get('_token'))) {
                $valutazioneFiguraProfessionaleRepository->remove($valutazioneFiguraProfessionale, true);
            }
        }

        return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{page}', name: 'app_valutazione_figura_professionale_index', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function index(ValutazioneFiguraProfessionaleRepository $valutazioneFiguraProfessionaleRepository, int $page = 1): Response
    {
        $schedePerPagina = 10;
        $offset = $schedePerPagina * $page - $schedePerPagina;
        $totaleSchede = $valutazioneFiguraProfessionaleRepository->contaSchede();
        $pagineTotali = ceil($totaleSchede / $schedePerPagina);
        return $this->render('valutazione_figura_professionale/index.html.twig', [
            'valutazione_figura_professionales' => $valutazioneFiguraProfessionaleRepository->findBy([], null, $schedePerPagina, $offset),
            'pagina' => $page,
            'pagine_totali' => $pagineTotali
        ]);
    }

    #[Route('/{pathName}/new', name: 'app_valutazione_figura_professionale_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $valutazioneFiguraProfessionale = new ValutazioneFiguraProfessionale();
        $form = $this->createForm(ValutazioneFiguraProfessionaleFormType::class, $valutazioneFiguraProfessionale);
        $form->handleRequest($request);
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $valutazioneFiguraProfessionale->setOperatore($user);
            $schedaPai->addIdValutazioneFiguraProfessionale($valutazioneFiguraProfessionale);
            $valutazioneFiguraProfessionaleRepository = $this->entityManager->getRepository(ValutazioneFiguraProfessionale::class);
            $valutazioneFiguraProfessionaleRepository->add($valutazioneFiguraProfessionale, true);
            $this->entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('valutazione_figura_professionale/new.html.twig', [
            'valutazione_figura_professionale' => $valutazioneFiguraProfessionale,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/show/{id}', name: 'app_valutazione_figura_professionale_show', methods: ['GET'])]
    public function show(ValutazioneFiguraProfessionale $valutazioneFiguraProfessionale): Response
    {
        $variabileTest = null;
        return $this->render('valutazione_figura_professionale/show.html.twig', [
            'valutazione_figura_professionale' => $valutazioneFiguraProfessionale,
            'variabileTest' => $variabileTest
        ]);
    }

    #[Route('/{id}/edit', name: 'app_valutazione_figura_professionale_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ValutazioneFiguraProfessionale $valutazioneFiguraProfessionale, ValutazioneFiguraProfessionaleRepository $valutazioneFiguraProfessionaleRepository): Response
    {
        $form = $this->createForm(ValutazioneFiguraProfessionaleFormType::class, $valutazioneFiguraProfessionale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $valutazioneFiguraProfessionaleRepository->add($valutazioneFiguraProfessionale, true);

            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('valutazione_figura_professionale/edit.html.twig', [
            'valutazione_figura_professionale' => $valutazioneFiguraProfessionale,
            'form' => $form,
        ]);
    }
}
