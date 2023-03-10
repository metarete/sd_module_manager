<?php

namespace App\Controller\ControllerPAI;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
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
use App\Service\SDManagerClientApiService;
use App\Service\BisogniService;
use App\Service\AltraTipologiaAssistenzaService;
use App\Service\ApprovaSchedaService;
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
    private $SdManagerClientApiService;
    private $altraTipologiaAssistenzaService;
    private $bisogniService;
    private $approvaSchedaService;


    public function __construct(WorkflowInterface $schedePaiCreatingStateMachine, EntityManagerInterface $entityManager, SdManagerClientApiService $SdManagerClientApiService, AltraTipologiaAssistenzaService $altraTipologiaAssistenzaService, BisogniService $bisogniService, ApprovaSchedaService $approvaSchedaService)
    {
        $this->workflow = $schedePaiCreatingStateMachine;
        $this->entityManager = $entityManager;
        $this->SdManagerClientApiService = $SdManagerClientApiService;
        $this->altraTipologiaAssistenzaService = $altraTipologiaAssistenzaService;
        $this->bisogniService = $bisogniService;
        $this->approvaSchedaService = $approvaSchedaService;
    }


    #[Route('/{page}', name: 'app_scheda_pai_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, SchedaPAIRepository $schedaPAIRepository, int $page = 1): Response
    {

        //assistiti
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $assistiti = $assistitiRepository->findAll();
        //controllo login
        $user = $this->getUser();



        //parametri per calcolo tabella
        $ruoloUser = $user->getRoles();
        $idUser = $user->getId();
        //filtri
        $stato = $request->request->get('filtro_stato');
        $operatore = $request->request->get('filtro_operatore');
        $lista = $userRepository->findAllUsername();
        $numeroSchedeVisibiliPerPagina = $request->request->get('filtro_numero_schede');
        $listaUsername = [];
        for ($i = 0; $i < count($lista); $i++) {
            $listaUsername[$i] = $lista[$i]['username'];
        }

        //calcolo tabella
        $schedaPais = null;

        if ($numeroSchedeVisibiliPerPagina == null)
            $schedePerPagina = 10;
        else
            $schedePerPagina = $numeroSchedeVisibiliPerPagina;

        $offset = $schedePerPagina * $page - $schedePerPagina;


        if ($ruoloUser[0] == "ROLE_ADMIN") {
            if ($stato == null || $stato == "") {
                if ($operatore == '' || $operatore == null || $operatore == 'tutti')
                    $schedaPais = $schedaPAIRepository->findBy([], array('id' => 'DESC'), $schedePerPagina, $offset);
                else
                    $schedaPais = $schedaPAIRepository->findStatoUsernameSchedePai($operatore, null, $schedePerPagina, $page);
            } else {
                if ($operatore == '' || $operatore == null || $operatore == 'tutti')
                    $schedaPais = $schedaPAIRepository->selectStatoSchedePai($stato, $page, $schedePerPagina);
                else
                    $schedaPais = $schedaPAIRepository->findStatoUsernameSchedePai($operatore, $stato, $schedePerPagina, $page);
            }
        }




        /*else if ($ruoloUser[0] == "ROLE_USER") {
            if ($stato == null || $stato == "") {
                $schedaPais = $schedaPAIRepository->findUserSchedePai($idUser, null, $ordinamentoId, $schedePerPagina, $page);
            } else {
                $schedaPais = $schedaPAIRepository->findUserSchedePai($idUser, $stato, $ordinamentoId, $schedePerPagina, $page);
            }
        }*/


        //calcolo pagine per paginatore
        $totaleSchede = $schedaPAIRepository->contaSchedePai($ruoloUser[0], $idUser, $stato);
        $pagineTotali = ceil($totaleSchede / $schedePerPagina);

        if ($pagineTotali == 0)
            $pagineTotali = 1;

        //nome path
        $pathName = 'app_scheda_pai_index';

        //calcolo valori delle schede per le scadenze delle scale -> nel listener

        /*for($i=0; $i<count($schedaPais); $i++){
            $schedaPais[$i]->setBarthelNumberToday();
            $schedaPais[$i]->setCorrectBarthelNumberToday();
            $schedaPais[$i]->setBradenNumberToday();
            $schedaPais[$i]->setCorrectBradenNumberToday();
            $schedaPais[$i]->setSpmsqNumberToday();
            $schedaPais[$i]->setCorrectSpmsqNumberToday();
            $schedaPais[$i]->setTinettiNumberToday();
            $schedaPais[$i]->setCorrectTinettiNumberToday();
            $schedaPais[$i]->setVasNumberToday();
            $schedaPais[$i]->setCorrectVasNumberToday();
            $schedaPais[$i]->setLesioniNumberToday();
            $schedaPais[$i]->setCorrectLesioniNumberToday();
        }*/

        //alert
        $session = $request->getSession();
        $alertSincronizzazione = $session->get('alertSincronizzazione');
        if ($alertSincronizzazione == 'completata') {
            $this->addFlash(
                'Successo',
                'Sincronizzazione completata con successo!'
            );
        }
        elseif($alertSincronizzazione == 'errore'){
            $this->addFlash(
                'Fallimento',
                'Sincronizzazione fallita!'
            );
        }
        elseif($alertSincronizzazione == 'chiusuraFallita'){
            $this->addFlash(
                'Fallimento',
                'Chiusura Fallita! Per chiudere una scheda ?? necessario aver compilato tutte le
                scale di valutazione necessarie, la chisura servizio e almeno una valutazione professionale 
                per operatore coinvolto'
            );
        }
        elseif($alertSincronizzazione == 'chiusuraCompletata'){
            $this->addFlash(
                'Successo',
                'Chiusura Completata con successo!'
            );
        }
        elseif($alertSincronizzazione == 'approvazioneFallita'){
            $this->addFlash(
                'Fallimento',
                'Impossibile approvare la scheda. Per approvare la scheda ?? necessario impostare un
                operatore principale andando in configura'
            );
        }
        $session->set('alertSincronizzazione', '');
        
        return $this->render('scheda_pai/index.html.twig', [
            'scheda_pais' => $schedaPais,
            'pagina' => $page,
            'pagine_totali' => $pagineTotali,
            'schede_per_pagina' => $schedePerPagina,
            'operatore' => $operatore,
            'stato' => $stato,
            'user' => $user,
            'assistiti' => $assistiti,
            'listaUsername' => $listaUsername,
            'pathName' => $pathName
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
            $frequenzaBarthel = $schedaPAI->getFrequenzaBarthel();
            $frequenzaBraden = $schedaPAI->getFrequenzaBraden();
            $frequenzaSpmsq = $schedaPAI->getFrequenzaSpmsq();
            $frequenzaTinetti = $schedaPAI->getFrequenzaTinetti();
            $frequenzaVas = $schedaPAI->getFrequenzaVas();
            $frequenzaLesioni = $schedaPAI->getFrequenzaLesioni();
            $dataInizio = $schedaPAI->getDataInizio();
            $dataFine = $schedaPAI->getDataFine();
            $numeroGiorniTotali = $dataFine->diff($dataInizio)->days;
            if ($frequenzaBarthel == 0) {
                $numeroBarthelCorretto = 0;
            } else
                $numeroBarthelCorretto = (int)($numeroGiorniTotali / $frequenzaBarthel);
            if ($frequenzaBraden == 0) {
                $numeroBradenCorretto = 0;
            } else
                $numeroBradenCorretto = (int)($numeroGiorniTotali / $frequenzaBraden);
            if ($frequenzaSpmsq == 0) {
                $numeroSpmsqCorretto = 0;
            } else
                $numeroSpmsqCorretto = (int)($numeroGiorniTotali / $frequenzaSpmsq);
            if ($frequenzaTinetti == 0) {
                $numeroTinettiCorretto = 0;
            } else
                $numeroTinettiCorretto = (int)($numeroGiorniTotali / $frequenzaTinetti);
            if ($frequenzaVas == 0) {
                $numeroVasCorretto = 0;
            } else
                $numeroVasCorretto = (int)($numeroGiorniTotali / $frequenzaVas);
            if ($frequenzaLesioni == 0) {
                $numeroLesioniCorretto = 0;
            } else
                $numeroLesioniCorretto = (int)($numeroGiorniTotali / $frequenzaLesioni);

            $schedaPAI->setNumeroBarthelCorretto($numeroBarthelCorretto);
            $schedaPAI->setNumeroBradenCorretto($numeroBradenCorretto);
            $schedaPAI->setNumeroSpmsqCorretto($numeroSpmsqCorretto);
            $schedaPAI->setNumeroTinettiCorretto($numeroTinettiCorretto);
            $schedaPAI->setNumeroVasCorretto($numeroVasCorretto);
            $schedaPAI->setNumeroLesioniCorretto($numeroLesioniCorretto);
            $schedaPAIRepository->add($schedaPAI, true);

            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scheda_pai/new.html.twig', [
            'scheda_pai' => $schedaPAI,
            'form' => $form,
        ]);
    }

    #[Route('/{pathName}/show/{id}', name: 'app_scheda_pai_show', methods: ['GET'])]
    public function show(SchedaPAI $schedaPAI, string $pathName): Response
    {
        //assistiti
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
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
        $altraTipologiaAssistenza = [];
        $altraTipologiaAssistenza = $this->altraTipologiaAssistenzaService->getAltreTipologieAssistenza($valutazioneGenerale);
        $bisogni = [];
        $bisogni = $this->bisogniService->getBisogni($valutazioneGenerale);

        $user = $this->getUser();


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
            'altra_tipologia_assistenza' => $altraTipologiaAssistenza,
            'bisogni' => $bisogni,
            'pathName' => $pathName,
            'user' => $user,
        ]);
    }

    #[Route('/{pathName}/{id}/edit/', name: 'app_scheda_pai_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SchedaPAI $schedaPAI, SchedaPAIRepository $schedaPAIRepository, string $pathName): Response
    {
        $form = $this->createForm(SchedaPAIType::class, $schedaPAI);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $frequenzaBarthel = $schedaPAI->getFrequenzaBarthel();
            $frequenzaBraden = $schedaPAI->getFrequenzaBraden();
            $frequenzaSpmsq = $schedaPAI->getFrequenzaSpmsq();
            $frequenzaTinetti = $schedaPAI->getFrequenzaTinetti();
            $frequenzaVas = $schedaPAI->getFrequenzaVas();
            $frequenzaLesioni = $schedaPAI->getFrequenzaLesioni();
            $dataInizio = $schedaPAI->getDataInizio();
            $dataFine = $schedaPAI->getDataFine();
            $numeroGiorniTotali = $dataFine->diff($dataInizio)->days;
            if ($frequenzaBarthel == 0) {
                $numeroBarthelCorretto = 0;
            } else
                $numeroBarthelCorretto = (int)($numeroGiorniTotali / $frequenzaBarthel);
            if ($frequenzaBraden == 0) {
                $numeroBradenCorretto = 0;
            } else
                $numeroBradenCorretto = (int)($numeroGiorniTotali / $frequenzaBraden);
            if ($frequenzaSpmsq == 0) {
                $numeroSpmsqCorretto = 0;
            } else
                $numeroSpmsqCorretto = (int)($numeroGiorniTotali / $frequenzaSpmsq);
            if ($frequenzaTinetti == 0) {
                $numeroTinettiCorretto = 0;
            } else
                $numeroTinettiCorretto = (int)($numeroGiorniTotali / $frequenzaTinetti);
            if ($frequenzaVas == 0) {
                $numeroVasCorretto = 0;
            } else
                $numeroVasCorretto = (int)($numeroGiorniTotali / $frequenzaVas);
            if ($frequenzaLesioni == 0) {
                $numeroLesioniCorretto = 0;
            } else
                $numeroLesioniCorretto = (int)($numeroGiorniTotali / $frequenzaLesioni);

            $schedaPAI->setNumeroBarthelCorretto($numeroBarthelCorretto);
            $schedaPAI->setNumeroBradenCorretto($numeroBradenCorretto);
            $schedaPAI->setNumeroSpmsqCorretto($numeroSpmsqCorretto);
            $schedaPAI->setNumeroTinettiCorretto($numeroTinettiCorretto);
            $schedaPAI->setNumeroVasCorretto($numeroVasCorretto);
            $schedaPAI->setNumeroLesioniCorretto($numeroLesioniCorretto);

            if ($form->getClickedButton() && 'salvaEApprova' === $form->getClickedButton()->getName()) {
                $schedaPAI->setCurrentPlace('approvata');
            }

            $schedaPAIRepository->add($schedaPAI, true);



            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scheda_pai/edit.html.twig', [
            'scheda_pai' => $schedaPAI,
            'form' => $form,
            'pathName' => $pathName
        ]);
    }

    #[Route('/{pathName}/delete/{id}', name: 'app_scheda_pai_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, SchedaPAI $schedaPAI, SchedaPAIRepository $schedaPAIRepository, string $pathName): Response
    {

        if ($this->isCsrfTokenValid('delete' . $schedaPAI->getId(), $request->get('_token'))) {

            $schedaPAIRepository->remove($schedaPAI, true);
        }


        return $this->redirectToRoute($pathName, [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'app_scheda_pai_pdf', methods: ['GET'])]
    public function generatePdf(SchedaPAI $schedaPAI)
    {
        //data creazione pdf
        $dataCreazione = date("d/m/Y");

        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
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
        $idAssistito = $schedaPAI->getIdAssistito();
        $assistito = $assistitiRepository->findOneById($idAssistito);
        $variabileTest = 1;
        $altraTipologiaAssistenza = [];
        $altraTipologiaAssistenza = $this->altraTipologiaAssistenzaService->getAltreTipologieAssistenza($valutazioneGenerale);
        $bisogni = [];
        $bisogni = $this->bisogniService->getBisogni($valutazioneGenerale);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');


        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $img = file_get_contents(
            "/app/public/image/logo.jpeg"
        );
        $imgTitle = file_get_contents(
            "/app/public/image/PAI.jpg"
        );
        $imgLogoMetarete = file_get_contents(
            "/app/public/image/logo-metarete.png"
        );
        $image64 = base64_encode($img);
        $image64Title = base64_encode($imgTitle);
        $image64Metarete = base64_encode($imgLogoMetarete);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('template_pdf.html.twig', [
            'title' => "Scheda PAI",
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
            'altra_tipologia_assistenza' => $altraTipologiaAssistenza,
            'bisogni' => $bisogni,
            'image64' => $image64,
            'image64Title' => $image64Title,
            'image64Metarete' => $image64Metarete,
            'dataCreazione' => $dataCreazione,
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
            "Attachment" => false
        ]);
    }
    #[Route('/{pathName}/anagrafica_assistito/{id}', name: 'app_scheda_pai_anagrafica_assistito', methods: ['GET'])]
    public function datiAssistito(SchedaPAI $schedaPAI, string $pathName)
    {
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        $idAssistito = $schedaPAI->getIdAssistito();
        $assistito = $assistitiRepository->findOneById($idAssistito);
        $variabileTest = 1;

        return $this->render('scheda_pai/show_assistito.html.twig', [
            'scheda_pai' => $schedaPAI,
            'assistito' => $assistito,
            'variabileTest' => $variabileTest,
            'pathName' => $pathName,
        ]);
    }
    #[Route('/{pathName}/chiusura_scheda/{id}', name: 'app_scheda_pai_chiusura', methods: ['GET'])]
    public function chiudiScheda(SchedaPAI $schedaPAI, string $pathName, Request $request): Response
    {
        $idScheda = $schedaPAI->getId();
        $barthelRepository = $this->entityManager->getRepository(Barthel::class);
        $bradenRepository = $this->entityManager->getRepository(Braden::class);
        $spmsqRepository = $this->entityManager->getRepository(SPMSQ::class);
        $tinettiRepository = $this->entityManager->getRepository(Tinetti::class);
        $vasRepository = $this->entityManager->getRepository(Vas::class);
        $lesioniRepository = $this->entityManager->getRepository(Lesioni::class);
        $numeroBarthelPresenti = $barthelRepository->findByBarthelPerScheda($idScheda);
        $numeroBarthelCorretto = $schedaPAI->getNumeroBarthelCorretto();
        $numeroBradenPresenti = $bradenRepository->findByBradenPerScheda($idScheda);
        $numeroBradenCorretto = $schedaPAI->getNumeroBradenCorretto();
        $numeroSpmsqPresenti = $spmsqRepository->findBySpmsqPerScheda($idScheda);
        $numeroSpmsqCorretto = $schedaPAI->getNumeroSpmsqCorretto();
        $numeroTinettiPresenti = $tinettiRepository->findByTinettiPerScheda($idScheda);
        $numeroTinettiCorretto = $schedaPAI->getNumeroTinettiCorretto();
        $numeroVasPresenti = $vasRepository->findByVasPerScheda($idScheda);
        $numeroVasCorretto = $schedaPAI->getNumeroVasCorretto();
        $numeroLesioniPresenti = $lesioniRepository->findByLesioniPerScheda($idScheda);
        $numeroLesioniCorretto = $schedaPAI->getNumeroLesioniCorretto();
        $numeroOperatoriInf = count($schedaPAI->getidOperatoreSecondarioInf());
        $numeroOperatoriTdr = count($schedaPAI->getidOperatoreSecondarioTdr());
        $numeroOperatoriLog = count($schedaPAI->getidOperatoreSecondarioLog());
        $numeroOperatoriAsa = count($schedaPAI->getidOperatoreSecondarioAsa());
        $numeroOperatoriOss = count($schedaPAI->getidOperatoreSecondarioOss());
        $numeroValutazioneProfessionaliMinime = $numeroOperatoriInf + $numeroOperatoriTdr + $numeroOperatoriLog + $numeroOperatoriAsa + $numeroOperatoriOss;
        $numeroValutazioniProfessionali = count($schedaPAI->getIdValutazioneFiguraProfessionale());
        $chiusuraServizio = $schedaPAI->getIdChiusuraServizio();

        if ($numeroBarthelPresenti == $numeroBarthelCorretto && $numeroBradenPresenti == $numeroBradenCorretto && $numeroSpmsqPresenti == $numeroSpmsqCorretto && $numeroTinettiPresenti == $numeroTinettiCorretto && $numeroVasPresenti == $numeroVasCorretto && $numeroLesioniPresenti == $numeroLesioniCorretto && $chiusuraServizio != null && $numeroValutazioniProfessionali >= $numeroValutazioneProfessionaliMinime) {
            if ($chiusuraServizio->getRinnovo() == false) {
                $schedaPAI->setCurrentPlace('chiusa');
                $session = $request->getSession();
                $session->set('alertSincronizzazione', 'chiusuraCompletata');
                $this->entityManager->flush();
            } else {
                $schedaPAI->setCurrentPlace('chiusa_con_rinnovo');
                $session = $request->getSession();
                $session->set('alertSincronizzazione', 'chiusuraCompletata');
                $this->entityManager->flush();
            }
        } else {
            //alert fallimento chiusura
            $session = $request->getSession();
            $session->set('alertSincronizzazione', 'chiusuraFallita');
            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', []);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', []);
        }

        if ($pathName == 'app_scadenzario_index') {
            return $this->redirectToRoute('app_scadenzario_index', [], Response::HTTP_SEE_OTHER);
        } else
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/sincronizza_progetti', name: 'app_scheda_pai_sincronizza', methods: ['GET'])]
    public function sincronizza(Request $request)
    {
        $session = $request->getSession();

        $dataInizio = date('d-m-Y', strtotime('-3 month'));
        $dataFine = date('d-m-Y', strtotime('+3 month'));

        $this->SdManagerClientApiService->sincAssistiti();
        if($this->SdManagerClientApiService->getCodiceResponseAssistiti()!= 200){
            $session->set('alertSincronizzazione', 'errore');
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->SdManagerClientApiService->sincOperatori();
        if($this->SdManagerClientApiService->getCodiceResponseOperatori()!= 200){
            $session->set('alertSincronizzazione', 'errore');
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        $this->SdManagerClientApiService->sincProgetti($dataInizio, $dataFine);
        if($this->SdManagerClientApiService->getCodiceResponseProgetti()!= 200){
            $session->set('alertSincronizzazione', 'errore');
            return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $session->set('alertSincronizzazione', 'completata');

        return $this->redirectToRoute('app_scheda_pai_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/approva_scheda_pai/{id}', name: 'app_scheda_pai_approva', methods: ['GET'])]
    public function approva(Request $request , SchedaPAI $schedaPAI, string $pathName)
    {
        $this->approvaSchedaService->approva($schedaPAI);
        if($schedaPAI->getIdOperatorePrincipale()==null){
            $session = $request->getSession();
            $session->set('alertSincronizzazione', 'approvazioneFallita');
        }
        
        return $this->redirectToRoute($pathName, [], Response::HTTP_SEE_OTHER);
    }
}
