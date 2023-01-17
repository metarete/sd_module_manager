<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\ParereMMG;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\ParereMMGFormType;
use App\Repository\ParereMMGRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/parere_mmg')]
class ParereMMGController extends AbstractController
{
    private $entityManager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $this->managerRegistry->getManager();
    }
    #[Route('/[page}', name: 'app_parere_mmg_index',requirements: ['page' => '\d+'], methods: ['GET'])]
    public function index(ParereMMGRepository $parereMMGRepository, int $page=1): Response
    {
        $schedePerPagina = 10;
        $offset = $schedePerPagina*$page-$schedePerPagina;
        $totaleSchede = $parereMMGRepository->contaSchede();
        $pagineTotali = ceil($totaleSchede/$schedePerPagina);
        return $this->render('parere_mmg/index.html.twig', [
            'parere_mmgs' => $parereMMGRepository->findBy([], null, $schedePerPagina, $offset ),
            'pagina'=>$page,
            'pagine_totali'=>$pagineTotali
        ]);
    }

    #[Route('/{pathName}/new', name: 'app_parere_mmg_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $parereMMG = new ParereMMG();
        $form = $this->createForm(ParereMMGFormType::class, $parereMMG);
        $form->handleRequest($request);
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $parereMMG->setOperatore($user);
            $schedaPai->setIdParereMmg($parereMMG);
            $parereMMGRepository = $this->entityManager->getRepository(ParereMMG::class);
            $parereMMGRepository->add($parereMMG, true);
            $this->entityManager->flush();

            if($pathName == 'app_scadenzario_index'){
                return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
            }
            else
                return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parere_mmg/new.html.twig', [
            'parere_mmg' => $parereMMG,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_parere_mmg_show', methods: ['GET'])]
    public function show(ParereMMG $parereMMG): Response
    {
        $variabileTest = null;
        return $this->render('parere_mmg/show.html.twig', [
            'parere_mmg' => $parereMMG,
            'variabileTest' => $variabileTest
        ]);
    }

    #[Route('/{id}/edit', name: 'app_parere_mmg_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParereMMG $parereMMG, ParereMMGRepository $parereMMGRepository): Response
    {
        $form = $this->createForm(ParereMMGFormType::class, $parereMMG);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parereMMGRepository->add($parereMMG, true);

            return $this->redirectToRoute('app_parere_mmg_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parere_mmg/edit.html.twig', [
            'parere_mmg' => $parereMMG,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_parere_mmg_delete', methods: ['POST'])]
    public function delete(Request $request, ParereMMG $parereMMG, ParereMMGRepository $parereMMGRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parereMMG->getId(), $request->request->get('_token'))) {
            $parereMMGRepository->remove($parereMMG, true);
        }

        return $this->redirectToRoute('app_parere_mmg_index', [], Response::HTTP_SEE_OTHER);
    }
}
