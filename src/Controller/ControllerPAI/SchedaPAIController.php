<?php

namespace App\Controller\ControllerPAI;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Paziente;
use App\Entity\EntityPAI\Vas;
use App\Entity\EntityPAI\SPMSQ;
use App\Entity\EntityPAI\Braden;
use App\Entity\EntityPAI\Barthel;
use App\Entity\EntityPAI\Lesioni;
use App\Entity\EntityPAI\Tinetti;
use App\Entity\EntityPAI\SchedaPAI;
use App\Form\FormPAI\SchedaPAIType;
use App\Repository\SchedaPAIRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/scheda_pai')]
class SchedaPAIController extends AbstractController
{
    private $workflow;
    private $entityManager;


    public function __construct(WorkflowInterface $schedePaiCreatingStateMachine, EntityManagerInterface $entityManager)
    {
        $this->workflow = $schedePaiCreatingStateMachine;
        $this->entityManager = $entityManager;
    }


    #[Route('/{page}', name: 'app_scheda_pai_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, SchedaPAIRepository $schedaPAIRepository, int $page = 1): Response
    {

        //assistiti
        $em = $this->entityManager;
        $assistitiRepository = $em->getRepository(Paziente::class);
        $assistiti = $assistitiRepository->findAll();
        //controllo login
        $user = $this->getUser();



        //parametri per calcolo tabella
        $ruoloUser = $user->getRoles();
        $idUser = $user->getId();
        //filtri
        $stato = $request->request->get('filtro_stato');
        $ordinamentoId = $request->request->get('filtro_id');
        $numeroSchedeVisibiliPerPagina = $request->request->get('filtro_numero_schede');


        //calcolo tabella
        $schedaPais = null;

        if ($numeroSchedeVisibiliPerPagina == null)
            $schedePerPagina = 10;
        else
            $schedePerPagina = $numeroSchedeVisibiliPerPagina;

        $offset = $schedePerPagina * $page - $schedePerPagina;


        if ($ruoloUser[0] == "ROLE_ADMIN") {
            if ($stato != null) {
                $schedaPais = $schedaPAIRepository->selectStatoSchedePai($stato, $ordinamentoId, $page, $schedePerPagina);
            } else {
                if ($ordinamentoId == null || $ordinamentoId == "" || $ordinamentoId == 'Crescente') {
                    $schedaPais = $schedaPAIRepository->findBy([], array('id' => 'ASC'), $schedePerPagina, $offset);
                } else if ($ordinamentoId == 'Decrescente') {
                    $schedaPais = $schedaPAIRepository->findBy([], array('id' => 'DESC'), $schedePerPagina, $offset);
                }
            }
        } else if ($ruoloUser[0] == "ROLE_USER") {
            if ($stato == null || $stato == "") {
                $schedaPais = $schedaPAIRepository->findUserSchedePai($idUser, null, $ordinamentoId, $schedePerPagina, $page);
            } else {
                $schedaPais = $schedaPAIRepository->findUserSchedePai($idUser, $stato, $ordinamentoId, $schedePerPagina, $page);
            }
        }
        //calcolo pagine per paginatore
        $totaleSchede = $schedaPAIRepository->contaSchedePai($ruoloUser[0], $idUser, $stato);
        $pagineTotali = ceil($totaleSchede / $schedePerPagina);

        if ($pagineTotali == 0)
            $pagineTotali = 1;
        return $this->render('scheda_pai/index.html.twig', [
            'scheda_pais' => $schedaPais,
            'pagina' => $page,
            'pagine_totali' => $pagineTotali,
            'schede_per_pagina' => $schedePerPagina,
            'ordinamento' => $ordinamentoId,
            'stato' => $stato,
            'user' => $user,
            'assistiti' => $assistiti,
        ]);
    }




    #[Route('/new', name: 'app_scheda_pai_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SchedaPAIRepository $schedaPAIRepository): Response
    {
        $schedaPAI = new SchedaPAI();
        $form = $this->createForm(SchedaPAIType::class, $schedaPAI);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $schedaPAI->setCurrentPlace('nuova');
            if ($this->workflow->can($schedaPAI, 'approva')) {
                $this->workflow->apply($schedaPAI, 'approva');
            }
            $schedaPAIRepository->add($schedaPAI, true);

            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scheda_pai/new.html.twig', [
            'scheda_pai' => $schedaPAI,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_scheda_pai_show', methods: ['GET'])]
    public function show(SchedaPAI $schedaPAI): Response
    {
        //assistiti
        $em = $this->entityManager;
        $assistitiRepository = $em->getRepository(Paziente::class);
        $assistiti = $assistitiRepository->findAll();
        $idAssistito = $schedaPAI->getIdAssistito();
        $assistito = $assistitiRepository->findOneById($idAssistito);

        $valutazioneGenerale = $schedaPAI->getIdValutazioneGenerale();
        $valutazioniFiguraProfessionale = $schedaPAI->getIdValutazioneFiguraProfessionale();
        $parereMMG = $schedaPAI->getIdParereMmg();
        $barthel = $schedaPAI->getIdBarthel();
        $braden = $schedaPAI->getIdBraden();
        $spmsq = $schedaPAI->getIdSpmsq();
        $tinetti = $schedaPAI->getIdTinetti();
        $vas = $schedaPAI->getIdVas();
        $lesioni = $schedaPAI->getIdLesioni();
        $chiusuraServizio = $schedaPAI->getIdChiusuraServizio();
        $variabileTest = 1;
        return $this->render('scheda_pai_completa.html.twig', [
            'scheda_pai' => $schedaPAI,
            'valutazione_generale' => $valutazioneGenerale,
            'valutazioni_figura_professionale' => $valutazioniFiguraProfessionale,
            'parere_mmg' => $parereMMG,
            'barthels' => $barthel,
            'bradens' => $braden,
            'spmsqs' => $spmsq,
            'tinettis' => $tinetti,
            'vass' => $vas,
            'lesionis' => $lesioni,
            'chiusura_servizio' => $chiusuraServizio,
            'variabileTest' => $variabileTest,
            'assistito' => $assistito,
            'assistiti' => $assistiti,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_scheda_pai_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SchedaPAI $schedaPAI, SchedaPAIRepository $schedaPAIRepository): Response
    {
        $form = $this->createForm(SchedaPAIType::class, $schedaPAI);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $schedaPAIRepository->add($schedaPAI, true);

            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scheda_pai/edit.html.twig', [
            'scheda_pai' => $schedaPAI,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_scheda_pai_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, SchedaPAI $schedaPAI, SchedaPAIRepository $schedaPAIRepository): Response
    {

        if ($this->isCsrfTokenValid('delete' . $schedaPAI->getId(), $request->get('_token'))) {
            $schedaPAIRepository->remove($schedaPAI, true);
        }

        return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'app_scheda_pai_pdf', methods: ['GET'])]
    public function generatePdf(SchedaPAI $schedaPAI)
    {
        $valutazioneGenerale = $schedaPAI->getIdValutazioneGenerale();
        $valutazioniFiguraProfessionale = $schedaPAI->getIdValutazioneFiguraProfessionale();
        $parereMMG = $schedaPAI->getIdParereMmg();
        $barthel = $schedaPAI->getIdBarthel();
        $braden = $schedaPAI->getIdBraden();
        $spmsq = $schedaPAI->getIdSpmsq();
        $tinetti = $schedaPAI->getIdTinetti();
        $vas = $schedaPAI->getIdVas();
        $lesioni = $schedaPAI->getIdLesioni();
        $chiusuraServizio = $schedaPAI->getIdChiusuraServizio();
        $variabileTest = 1;

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('template_pdf.html.twig', [
            'title' => "Scheda Pai completa",
            'scheda_pai' => $schedaPAI,
            'valutazione_generale' => $valutazioneGenerale,
            'valutazioni_figura_professionale' => $valutazioniFiguraProfessionale,
            'parere_mmg' => $parereMMG,
            'barthels' => $barthel,
            'bradens' => $braden,
            'spmsqs' => $spmsq,
            'tinettis' => $tinetti,
            'vass' => $vas,
            'lesionis' => $lesioni,
            'chiusura_servizio' => $chiusuraServizio,
            'variabileTest' => $variabileTest
        ]);
        //$html .= '<link type="text/css" href="/absolute/path/to/pdf.css" rel="stylesheet" />';
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("SchedaPai.pdf", [
            "Attachment" => true
        ]);
    }
    #[Route('/anagrafica_assistito/{id}', name: 'app_scheda_pai_anagrafica_assistito', methods: ['GET'])]
    public function datiAssistito(SchedaPAI $schedaPAI)
    {
        $em = $this->entityManager;
        $assistitiRepository = $em->getRepository(Paziente::class);
        $idAssistito = $schedaPAI->getIdAssistito();
        $assistito = $assistitiRepository->findOneById($idAssistito);
        $variabileTest = 1;

        return $this->render('scheda_pai/show_assistito.html.twig', [
            'scheda_pai' => $schedaPAI,
            'assistito' => $assistito,
            'variabileTest' => $variabileTest
        ]);
    }
    #[Route('/chiusura_scheda/{id}', name: 'app_scheda_pai_chiusura', methods: ['GET'])]
    public function chiudiScheda(SchedaPAI $schedaPAI): Response
    {
        $idScheda = $schedaPAI->getId();
        $em = $this->entityManager;
        $barthelRepository = $em->getRepository(Barthel::class);
        $bradenRepository = $em->getRepository(Braden::class);
        $spmsqRepository = $em->getRepository(SPMSQ::class);
        $tinettiRepository = $em->getRepository(Tinetti::class);
        $vasRepository = $em->getRepository(Vas::class);
        $lesioniRepository = $em->getRepository(Lesioni::class);
        $numeroBarthelPresenti = $barthelRepository->findByBarthelPerScheda($idScheda);
        $numeroBarthelCorretto = $schedaPAI->getNumeroBarthel();
        $numeroBradenPresenti = $bradenRepository->findByBradenPerScheda($idScheda);
        $numeroBradenCorretto = $schedaPAI->getNumeroBraden();
        $numeroSpmsqPresenti = $spmsqRepository->findBySpmsqPerScheda($idScheda);
        $numeroSpmsqCorretto = $schedaPAI->getNumeroSpmsq();
        $numeroTinettiPresenti = $tinettiRepository->findByTinettiPerScheda($idScheda);
        $numeroTinettiCorretto = $schedaPAI->getNumeroTinetti();
        $numeroVasPresenti = $vasRepository->findByVasPerScheda($idScheda);
        $numeroVasCorretto = $schedaPAI->getNumeroVas();
        $numeroLesioniPresenti = $lesioniRepository->findByLesioniPerScheda($idScheda);
        $numeroLesioniCorretto = $schedaPAI->getNumeroLesioni();
        $numeroOperatoriInf = count($schedaPAI->getidOperatoreSecondarioInf());
        $numeroOperatoriTdr = count($schedaPAI->getidOperatoreSecondarioTdr());
        $numeroOperatoriLog = count($schedaPAI->getidOperatoreSecondarioLog());
        $numeroOperatoriAsa = count($schedaPAI->getidOperatoreSecondarioAsa());
        $numeroOperatoriOss = count($schedaPAI->getidOperatoreSecondarioOss());
        $numeroValutazioneProfessionaliMinime = $numeroOperatoriInf + $numeroOperatoriTdr + $numeroOperatoriLog + $numeroOperatoriAsa + $numeroOperatoriOss;
        $numeroValutazioniProfessionali = count($schedaPAI->getIdValutazioneFiguraProfessionale());
        $chiusuraServizio = $schedaPAI->getIdChiusuraServizio();
        
        if ($numeroBarthelPresenti == $numeroBarthelCorretto && $numeroBradenPresenti == $numeroBradenCorretto && $numeroSpmsqPresenti == $numeroSpmsqCorretto && $numeroTinettiPresenti == $numeroTinettiCorretto && $numeroVasPresenti == $numeroVasCorretto && $numeroLesioniPresenti == $numeroLesioniCorretto && $chiusuraServizio!=null && $numeroValutazioniProfessionali >= $numeroValutazioneProfessionaliMinime)
        {
            if($chiusuraServizio->getRinnovo() == false)
                $schedaPAI->setCurrentPlace('chiusa');
            else
                $schedaPAI->setCurrentPlace('chiusa_con_rinnovo');
        }        

        

        return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
    }
}
