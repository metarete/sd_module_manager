<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\ChiusuraForzata;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\ChiusuraForzataType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/chiusura_forzata')]
class ChiusuraForzataController extends AbstractController
{

    #[Route('/{pathName}/new', name: 'app_chiusura_forzata_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, string $pathName): Response
    {
        $schedaPAIRepository = $entityManager->getRepository(SchedaPAI::class);
        $schedaPai = $schedaPAIRepository->find($request->query->get('id_pai'));


        $chiusuraForzata = new ChiusuraForzata();
        $form = $this->createForm(ChiusuraForzataType::class, $chiusuraForzata);
        $form->handleRequest($request);

        if (!$schedaPai) {
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $chiusuraForzata->setOperatore($this->getUser());
            $schedaPai->setIdChiusuraForzata($chiusuraForzata);
            $chiusuraForzataRepository = $entityManager->getRepository(ChiusuraForzata::class);
            $chiusuraForzataRepository->add($chiusuraForzata, true);
            $schedaPai->setCurrentPlace('chiusura_forzata');
            $entityManager->flush();

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPai->getId()], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPai->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chiusura_forzata/new.html.twig', [
            'chiusura_forzata' => $chiusuraForzata,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

}
