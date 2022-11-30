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
    #[Route('/{page}', name: 'app_barthel_index',requirements: ['page' => '\d+'], methods: ['GET'])]
    public function index(BarthelRepository $barthelRepository, int $page=1): Response
    {
        $schedePerPagina = 10;
        $offset = $schedePerPagina*$page-$schedePerPagina;
        $totaleSchede = $barthelRepository->contaSchede();
        $pagineTotali = ceil($totaleSchede/$schedePerPagina);
        return $this->render('barthel/index.html.twig', [
            'barthels' => $barthelRepository->findBy([], null, $schedePerPagina, $offset ),
            'pagina'=>$page,
            'pagine_totali'=>$pagineTotali
        ]);
    }

    #[Route('/new', name: 'app_barthel_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $barthel = new Barthel();
        $form = $this->createForm(BarthelFormType::class, $barthel);
        $form->handleRequest($request);
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $schedaPai->addIdBarthel($barthel);
            //set parametro listener
            $barthelRepository = $this->entityManager->getRepository(Barthel::class);
            $barthelRepository->add($barthel, true);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_barthel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('barthel/new.html.twig', [
            'barthel' => $barthel,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_barthel_show', methods: ['GET'])]
    public function show(Barthel $barthel): Response
    {
        $variabileTest = null;
        return $this->render('barthel/show.html.twig', [
            'barthel' => $barthel,
            'variabileTest' => $variabileTest
        ]);
    }

    #[Route('/{id}/edit', name: 'app_barthel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Barthel $barthel, BarthelRepository $barthelRepository): Response
    {
        $form = $this->createForm(BarthelFormType::class, $barthel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $barthelRepository->add($barthel, true);

            return $this->redirectToRoute('app_barthel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('barthel/edit.html.twig', [
            'barthel' => $barthel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_barthel_delete', methods: ['POST'])]
    public function delete(Request $request, Barthel $barthel, BarthelRepository $barthelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$barthel->getId(), $request->request->get('_token'))) {
            $barthelRepository->remove($barthel, true);
        }

        return $this->redirectToRoute('app_barthel_index', [], Response::HTTP_SEE_OTHER);
    }
}
