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
    #[Route('/{page}', name: 'app_vas_index',requirements: ['page' => '\d+'], methods: ['GET'])]
    public function index(VasRepository $vasRepository, int $page=1): Response
    {
        $schedePerPagina = 10;
        $offset = $schedePerPagina*$page-$schedePerPagina;
        $totaleSchede = $vasRepository->contaSchede();
        $pagineTotali = ceil($totaleSchede/$schedePerPagina);
        return $this->render('vas/index.html.twig', [
            'vas' => $vasRepository->findBy([], null, $schedePerPagina, $offset ),
            'pagina'=>$page,
            'pagine_totali'=>$pagineTotali
        ]);
    }

    #[Route('/{pathName}/new', name: 'app_vas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, string $pathName): Response
    {
        $vas = new Vas();
        $form = $this->createForm(VasFormType::class, $vas);
        $form->handleRequest($request);
        $id_pai = $request->query->get('id_pai');
        $SchedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $SchedaPAIRepository->find($id_pai);
        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $vas->setOperatore($user);
            $schedaPai->addIdVas($vas);
            $vasRepository = $this->entityManager->getRepository(Vas::class);
            $vasRepository->add($vas, true);
            $this->entityManager->flush();


            if($pathName == 'app_scadenzario_index'){
                return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
            }
            else
                return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vas/new.html.twig', [
            'va' => $vas,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/show/{id}', name: 'app_vas_show', methods: ['GET'])]
    public function show(Vas $va): Response
    {
        $variabileTest = null;
        return $this->render('vas/show.html.twig', [
            'va' => $va,
            'variabileTest' => $variabileTest
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vas $va, VasRepository $vasRepository): Response
    {
        $form = $this->createForm(VasFormType::class, $va);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vasRepository->add($va, true);

            return $this->redirectToRoute('app_vas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vas/edit.html.twig', [
            'va' => $va,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vas_delete', methods: ['POST'])]
    public function delete(Request $request, Vas $va, VasRepository $vasRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$va->getId(), $request->request->get('_token'))) {
            $vasRepository->remove($va, true);
        }

        return $this->redirectToRoute('app_vas_index', [], Response::HTTP_SEE_OTHER);
    }
}
