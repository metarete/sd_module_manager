<?php

namespace App\Controller\ControllerPAI;

use App\Entity\User;
use App\Entity\Paziente;
use App\Entity\EntityPAI\Vas;
use App\Entity\EntityPAI\SPMSQ;
use App\Entity\EntityPAI\Braden;
use App\Entity\EntityPAI\Barthel;
use App\Entity\EntityPAI\Cdr;
use App\Entity\EntityPAI\Lesioni;
use App\Entity\EntityPAI\Painad;
use App\Entity\EntityPAI\Tinetti;
use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\EntityPAI\Pratica;
use App\Form\FormPAI\SchedaPAIType;
use App\Repository\SchedaPAIRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\SDManagerClientApiService;
use App\Service\BisogniService;
use App\Service\AltraTipologiaAssistenzaService;
use App\Service\ApprovaSchedaService;
use App\Service\CreazionePdfSchedaPaiService;
use App\Service\MailerGenerator;
use App\Service\SetterDatiSchedaPaiService;
use App\Service\SetterStatoVerificaSchedaPaiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Twig\FiltroColoriScadenzario;
use App\Twig\FiltroDropdownScadenzario;
use App\Twig\FiltroNomiStatiScadenzario;
use App\Twig\FiltroSimboloValutazioneScadenzario;
use Symfony\Component\Filesystem\Filesystem;

#[Route('/scheda_pai')]
class SchedaPAIController extends AbstractController
{
    private $entityManager;
    private $SdManagerClientApiService;
    private $altraTipologiaAssistenzaService;
    private $bisogniService;
    private $approvaSchedaService;
    private $filtroColoriScadenzario;
    private $filtroNomiStatiScadenzario;
    private $filtroDropdownScadenzario;
    private $filtroSimboloValutazioneScadenzario;
    private $setterStatoVerificaSchedaPaiService;
    private $setterDatiSchedaPaiService;
    private $creazionePdfSchedaPaiService;
    private $mailerGenerator;

    public function __construct(
        EntityManagerInterface $entityManager, 
        SdManagerClientApiService $SdManagerClientApiService, 
        AltraTipologiaAssistenzaService $altraTipologiaAssistenzaService, 
        BisogniService $bisogniService, 
        ApprovaSchedaService $approvaSchedaService, 
        FiltroColoriScadenzario $filtroColoriScadenzario, 
        FiltroNomiStatiScadenzario $filtroNomiStatiScadenzario, 
        FiltroDropdownScadenzario $filtroDropdownScadenzario, 
        FiltroSimboloValutazioneScadenzario $filtroSimboloValutazioneScadenzario , 
        SetterStatoVerificaSchedaPaiService $setterStatoVerificaSchedaPaiService, 
        SetterDatiSchedaPaiService $setterDatiSchedaPaiService,
        CreazionePdfSchedaPaiService $creazionePdfSchedaPaiService,
        MailerGenerator $mailerGenerator
        )
    {
        $this->entityManager = $entityManager;
        $this->SdManagerClientApiService = $SdManagerClientApiService;
        $this->altraTipologiaAssistenzaService = $altraTipologiaAssistenzaService;
        $this->bisogniService = $bisogniService;
        $this->approvaSchedaService = $approvaSchedaService;
        $this->filtroColoriScadenzario = $filtroColoriScadenzario;
        $this->filtroNomiStatiScadenzario = $filtroNomiStatiScadenzario;
        $this->filtroDropdownScadenzario = $filtroDropdownScadenzario;
        $this->filtroSimboloValutazioneScadenzario = $filtroSimboloValutazioneScadenzario;
        $this->setterStatoVerificaSchedaPaiService = $setterStatoVerificaSchedaPaiService;
        $this->setterDatiSchedaPaiService = $setterDatiSchedaPaiService;
        $this->creazionePdfSchedaPaiService = $creazionePdfSchedaPaiService;
        $this->mailerGenerator = $mailerGenerator;
    }


    #[Route('/{page}', name: 'app_scheda_pai_index', requirements: ['page' => '\d+'], methods: ['GET', 'POST'])]
    public function index(Request $request, SchedaPAIRepository $schedaPAIRepository, int $page = 1): Response
    {

        //repository
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $praticaRepository = $this->entityManager->getRepository(Pratica::class);
        
        //sessione
        $session = $request->getSession();
        //filtri
        if ($request->request->get('filtro_stato') != null || $request->request->get('filtro_stato') == $session->get('filtro_stato')) {
            if ($request->request->get('filtro_stato') == 'tutti')
                $session->set('filtro_stato', null);
            else {
                $session->set('filtro_stato', $request->request->get('filtro_stato'));
            }
        }
        if ($request->request->get('filtro_operatore') != null || $request->request->get('filtro_operatore') == $session->get('filtro_operatore')) {
            if ($request->request->get('filtro_operatore') == 'tutti')
                $session->set('filtro_operatore', null);
            else {
                $session->set('filtro_operatore', $request->request->get('filtro_operatore'));
            }
        }
        if ($request->request->get('filtro_numero_schede') != null || $request->request->get('filtro_numero_schede') == $session->get('filtro_numero_schede')) {
            if ($request->request->get('filtro_numero_schede') == 0)
                $session->set('filtro_numero_schede', null);
            else {
                $session->set('filtro_numero_schede', $request->request->get('filtro_numero_schede'));
            }
        }
        if ($request->request->get('filtro_pratica') != null || $request->request->get('filtro_pratica') == $session->get('filtro_pratica')) {
            if ($request->request->get('filtro_pratica') == 'tutte')
                $session->set('filtro_pratica', null);
            else {
                $session->set('filtro_pratica', $request->request->get('filtro_pratica'));
            }
        }
        if($request->request->get('filtro_ricerca') != null && $request->request->get('filtro_ricerca') != ""){
            $session->set('filtro_ricerca', $request->request->get('filtro_ricerca'));
        }
        else{
            $session->set('filtro_ricerca', null);
        }

        $listaPratiche = $praticaRepository->findAll();
        //elimino le pratiche senza progetti associati
        for($i=0; $i<count($listaPratiche); $i++){
            if($listaPratiche[$i]->getSchedaPais()->isEmpty()){
                array_splice($listaPratiche, $i, 1);
            }
        }
        
        //calcolo tabella
        $schedaPais = null;

        if ($session->get('filtro_numero_schede') == null)
            $schedePerPagina = 10;
        else
            $schedePerPagina = $session->get('filtro_numero_schede');

        $offset = $schedePerPagina * $page - $schedePerPagina;

        //applicazione filtri


        if ($session->get('filtro_pratica') == null || $session->get('filtro_pratica') == "" || $session->get('filtro_pratica') == "tutte") {
            if ($session->get('filtro_stato') == null || $session->get('filtro_stato') == "") {
                if ($session->get('filtro_operatore') == '' || $session->get('filtro_operatore') == null || $session->get('filtro_operatore') == 'tutti')
                    $schedaPais = $schedaPAIRepository->findBy([], array('id' => 'DESC'), $schedePerPagina, $offset);
                else{
                    $schedaPais1 = $schedaPAIRepository->findSchedePaiConOperatore($session->get('filtro_operatore'));
                    usort($schedaPais1, fn($a, $b) => $a->getId()-$b->getId());
                    $schedaPais1 = array_reverse($schedaPais1);
                    $schedaPais = [];
                    //costruisco l'elenco schede in base al filtro e alla pagina
                    for($i=(($page - 1) * $schedePerPagina); $i<$schedePerPagina*$page; $i++){
                        if($i<count($schedaPais1))
                            array_push($schedaPais,$schedaPais1[$i]);     
                    }
                }
            } else {
                if ($session->get('filtro_operatore') == '' || $session->get('filtro_operatore') == null || $session->get('filtro_operatore') == 'tutti')
                    $schedaPais = $schedaPAIRepository->findBy(['currentPlace' => $session->get('filtro_stato')], array('id' => 'DESC'), $schedePerPagina, $offset);
                else{
                    $schedaPais1 = $schedaPAIRepository->findSchedePaiConOperatore($session->get('filtro_operatore'));
                    $schedaPais2 = $schedaPAIRepository->findBy(['currentPlace' => $session->get('filtro_stato')], array('id' => 'DESC'));
                    $schedaPais3 = array_intersect($schedaPais1, $schedaPais2);
                    usort($schedaPais3, fn($a, $b) => $a->getId()-$b->getId());
                    $schedaPais3 = array_reverse($schedaPais3);
                    $schedaPais = [];
                    //costruisco l'elenco schede in base al filtro e alla pagina
                    for($i=(($page - 1) * $schedePerPagina); $i<$schedePerPagina*$page; $i++){
                        if($i<count($schedaPais3))
                            array_push($schedaPais,$schedaPais3[$i]);     
                    }
                }
            }
        } else {
            if ($session->get('filtro_stato') == null || $session->get('filtro_stato') == "") {
                if ($session->get('filtro_operatore') == '' || $session->get('filtro_operatore') == null || $session->get('filtro_operatore') == 'tutti'){
                    $schedaPais = $schedaPAIRepository->findBy(['adiwebPratica' => $session->get('filtro_pratica')], array('id' => 'DESC'), $schedePerPagina, $offset);
                }
                else{
                    $schedaPais1 = $schedaPAIRepository->findSchedePaiConOperatore($session->get('filtro_operatore'));
                    $schedaPais2 = $schedaPAIRepository->findBy(['adiwebPratica' => $session->get('filtro_pratica')], array('id' => 'DESC'));
                    $schedaPais3 = array_intersect($schedaPais1, $schedaPais2);
                    usort($schedaPais3, fn($a, $b) => $a->getId()-$b->getId());
                    $schedaPais3 = array_reverse($schedaPais3);
                    $schedaPais = [];
                    //costruisco l'elenco schede in base al filtro e alla pagina
                    for($i=(($page - 1) * $schedePerPagina); $i<$schedePerPagina*$page; $i++){
                        if($i<count($schedaPais3))
                            array_push($schedaPais,$schedaPais3[$i]);     
                    }
                }
            } else {
                if ($session->get('filtro_operatore') == '' || $session->get('filtro_operatore') == null || $session->get('filtro_operatore') == 'tutti')
                    $schedaPais = $schedaPAIRepository->findBy(['currentPlace' => $session->get('filtro_stato'), 'adiwebPratica' => $session->get('filtro_pratica')], array('id' => 'DESC'), $schedePerPagina, $offset);
                else {
                    $schedaPais1 = $schedaPAIRepository->findSchedePaiConOperatore($session->get('filtro_operatore'));
                    $schedaPais2 = $schedaPAIRepository->findBy(['currentPlace' => $session->get('filtro_stato'), 'adiwebPratica' => $session->get('filtro_pratica')], array('id' => 'DESC'));
                    $schedaPais3 = array_intersect($schedaPais1, $schedaPais2);
                    usort($schedaPais3, fn($a, $b) => $a->getId()-$b->getId());
                    $schedaPais3 = array_reverse($schedaPais3);
                    $schedaPais = [];
                    //costruisco l'elenco schede in base al filtro e alla pagina
                    for($i=(($page - 1) * $schedePerPagina); $i<$schedePerPagina*$page; $i++){
                        if($i<count($schedaPais3))
                            array_push($schedaPais,$schedaPais3[$i]);     
                    }
                }
            }
        }
    
        //utilizzo sistema di ricerca
        if($session->get('filtro_ricerca') != null && $session->get('filtro_ricerca') != ""){
            $schedaPais1 = $schedaPAIRepository->findByBarraRicerca($session->get('filtro_ricerca'));
            $schedaPais = [];
            //costruisco l'elenco schede in base al filtro e alla pagina
             for($i=(($page - 1) * $schedePerPagina); $i<$schedePerPagina*$page; $i++){
                if($i<count($schedaPais1))
                    array_push($schedaPais,$schedaPais1[$i]);     
            }
        }

        //calcolo pagine per paginatore
        //senza ricerca nella barra
        if($session->get('filtro_ricerca') == null && $session->get('filtro_ricerca') == ""){
            $totaleSchede = $schedaPAIRepository->contaSchedePai($session->get('filtro_operatore'), $session->get('filtro_stato'), $session->get('filtro_pratica'));
            $pagineTotali = ceil($totaleSchede / $schedePerPagina);
        }
        //con la ricerca della barra
        else{
            $totaleSchede = $schedaPAIRepository->contaSchedeInRicerca($session->get('filtro_ricerca'));
            $pagineTotali = ceil($totaleSchede / $schedePerPagina);
        }

        if ($pagineTotali == 0)
            $pagineTotali = 1;

        //nome path
        $pathName = 'app_scheda_pai_index';

        //calcolo valori delle schede per le scadenze delle scale -> nel listener


        //alert
        $alertSincronizzazione = $session->get('alertSincronizzazione');
        if ($alertSincronizzazione == 'completata') {
            $this->addFlash(
                'Successo',
                'Sincronizzazione completata con successo!'
            );
        } elseif ($alertSincronizzazione == 'errore') {
            $this->addFlash(
                'Fallimento',
                'Sincronizzazione fallita!'
            );
        } elseif ($alertSincronizzazione == 'chiusuraFallita') {
            $this->addFlash(
                'Fallimento',
                'Chiusura Fallita! Per chiudere una scheda è necessario aver compilato tutte le
                scale di valutazione necessarie, la chiusura servizio e almeno una valutazione professionale'
            );
        } elseif ($alertSincronizzazione == 'chiusuraFallitaPerStato') {
            $this->addFlash(
                'Fallimento',
                'Chiusura Fallita! Per chiudere una scheda è necessario averla verificata e 
                aver scelto se rinnovarla o non rinnovarla. Controlla se la scheda si trova nello 
                stato in attesa di chiusura o in attesa di chiusura con rinnovo'
            );
        } elseif ($alertSincronizzazione == 'chiusuraCompletata') {
            $this->addFlash(
                'Successo',
                'Chiusura Completata con successo!'
            );
        } elseif ($alertSincronizzazione == 'approvazioneFallita') {
            $this->addFlash(
                'Fallimento',
                'Impossibile approvare la scheda. Per approvare la scheda è necessario impostare un
                operatore principale andando in configura'
            );
        }
        $session->set('alertSincronizzazione', '');

        return $this->render('scheda_pai/index.html.twig', [
            'scheda_pais' => $schedaPais,
            'page' => $page,
            'pagine_totali' => $pagineTotali,
            'schede_per_pagina' => $schedePerPagina,
            'operatore' => $session->get('filtro_operatore'),
            'stato' => $session->get('filtro_stato'),
            'pratica' => $session->get('filtro_pratica'),
            'ricerca' => $session->get('filtro_ricerca'),
            'user' => $this->getUser(),
            'assistiti' => $assistitiRepository->findAll(),
            'listaOperatori' => $userRepository->findAll(),
            'listaPratiche' => $listaPratiche,
            'pathName' => $pathName,
            'filtroColoriScadenzario' => $this->filtroColoriScadenzario,
            'filtroNomiStatiScadenzario' => $this->filtroNomiStatiScadenzario,
            'filtroDropdownScadenzario' => $this->filtroDropdownScadenzario,
            'filtroSimboloValutazioneScadenzario' => $this->filtroSimboloValutazioneScadenzario
        ]);
    }

    #[Route('/{pathName}/show/{id}', name: 'app_scheda_pai_show', methods: ['GET'])]
    public function show(SchedaPAI $schedaPAI, string $pathName): Response
    {
        $this->denyAccessUnlessGranted('visualizza_scheda_completa', $schedaPAI);

        //assistiti
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);

        return $this->render('scheda_pai_completa.html.twig', [
            'scheda_pai' => $schedaPAI,
            'valutazione_generale' => $schedaPAI->getIdValutazioneGenerale(),
            'valutazioni_figura_professionale' => $schedaPAI->getIdValutazioneFiguraProfessionale(),
            'parere_mmg' => $schedaPAI->getIdParereMmg(),
            'barthels' => $schedaPAI->getIdBarthel(),
            'bradens' => $schedaPAI->getIdBraden(),
            'spmsqs' => $schedaPAI->getIdSpmsq(),
            'tinettis' => $schedaPAI->getIdTinetti(),
            'vass' => $schedaPAI->getIdVas(),
            'lesionis' => $schedaPAI->getIdLesioni(),
            'painads' => $schedaPAI->getIdPainad(),
            'cdrs' => $schedaPAI->getCdrs(),
            'chiusura_servizio' => $schedaPAI->getIdChiusuraServizio(),
            'chiusura_forzata' => $schedaPAI->getIdChiusuraForzata(),
            'assistito' => $schedaPAI->getAssistito(),
            'assistiti' => $assistitiRepository->findAll(),
            'altra_tipologia_assistenza' => $this->altraTipologiaAssistenzaService->getAltreTipologieAssistenza($schedaPAI->getIdValutazioneGenerale()),
            'bisogni' => $this->bisogniService->getBisogni($schedaPAI->getIdValutazioneGenerale()),
            'pathName' => $pathName,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/{pathName}/{id}/edit/', name: 'app_scheda_pai_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SchedaPAI $schedaPAI, SchedaPAIRepository $schedaPAIRepository, string $pathName): Response
    {
        $this->denyAccessUnlessGranted('configura', $schedaPAI);

        $form = $this->createForm(SchedaPAIType::class, $schedaPAI);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->setterDatiSchedaPaiService->settaDatiCompilazioneSchede($schedaPAI);

            if ($form->getClickedButton() && 'salvaEApprova' === $form->getClickedButton()->getName()) {
                $schedaPAI->setCurrentPlace('approvata');
            }

            $schedaPAIRepository->add($schedaPAI, true);

            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()], Response::HTTP_SEE_OTHER);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()], Response::HTTP_SEE_OTHER);
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
        $this->denyAccessUnlessGranted('elimina', $schedaPAI);

        if ($this->isCsrfTokenValid('delete' . $schedaPAI->getId(), $request->get('_token'))) {

            $schedaPAIRepository->remove($schedaPAI, true);
        }

        return $this->redirectToRoute($pathName, ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'app_scheda_pai_pdf', methods: ['GET'])]
    public function generatePdf(SchedaPAI $schedaPAI)
    {
        $html = $this->creazionePdfSchedaPaiService->generaHtmlPerPdf($schedaPAI);
        $this->creazionePdfSchedaPaiService->visualizzaPdf($html, $schedaPAI);
    }

    #[Route('/{pathName}/anagrafica_assistito/{id}', name: 'app_scheda_pai_anagrafica_assistito', methods: ['GET'])]
    public function datiAssistito(SchedaPAI $schedaPAI, string $pathName)
    {

        return $this->render('scheda_pai/show_assistito.html.twig', [
            'scheda_pai' => $schedaPAI,
            'assistito' => $schedaPAI->getAssistito(),
            'pathName' => $pathName,
        ]);
    }

    #[Route('/{pathName}/chiusura_scheda/{id}', name: 'app_scheda_pai_chiusura', methods: ['GET'])]
    public function chiudiScheda(SchedaPAI $schedaPAI, string $pathName, Request $request): Response
    {
        $this->denyAccessUnlessGranted('chiudi', $schedaPAI);

        $barthelRepository = $this->entityManager->getRepository(Barthel::class);
        $bradenRepository = $this->entityManager->getRepository(Braden::class);
        $spmsqRepository = $this->entityManager->getRepository(SPMSQ::class);
        $tinettiRepository = $this->entityManager->getRepository(Tinetti::class);
        $vasRepository = $this->entityManager->getRepository(Vas::class);
        $lesioniRepository = $this->entityManager->getRepository(Lesioni::class);
        $painadRepository = $this->entityManager->getRepository(Painad::class);
        $cdrRepository = $this->entityManager->getRepository(Cdr::class);
        $numeroBarthelPresenti = $barthelRepository->findByBarthelPerScheda($schedaPAI->getId());
        $numeroBarthelCorretto = $schedaPAI->getNumeroBarthelCorretto();
        $numeroBradenPresenti = $bradenRepository->findByBradenPerScheda($schedaPAI->getId());
        $numeroBradenCorretto = $schedaPAI->getNumeroBradenCorretto();
        $numeroSpmsqPresenti = $spmsqRepository->findBySpmsqPerScheda($schedaPAI->getId());
        $numeroSpmsqCorretto = $schedaPAI->getNumeroSpmsqCorretto();
        $numeroTinettiPresenti = $tinettiRepository->findByTinettiPerScheda($schedaPAI->getId());
        $numeroTinettiCorretto = $schedaPAI->getNumeroTinettiCorretto();
        $numeroVasPresenti = $vasRepository->findByVasPerScheda($schedaPAI->getId());
        $numeroVasCorretto = $schedaPAI->getNumeroVasCorretto();
        $numeroLesioniPresenti = $lesioniRepository->findByLesioniPerScheda($schedaPAI->getId());
        $numeroLesioniCorretto = $schedaPAI->getNumeroLesioniCorretto();
        $numeroPainadPresenti = $painadRepository->findByPainadPerScheda($schedaPAI->getId());
        $numeroPainadCorretto = $schedaPAI->getNumeroPainadCorretto();
        $numeroCdrPresenti = $cdrRepository->findByCdrPerScheda($schedaPAI->getId());
        $numeroCdrCorretto = $schedaPAI->getNumeroCdrCorretto();
        $numeroValutazioneProfessionaliMinime = 1;
        $numeroValutazioniProfessionali = count($schedaPAI->getIdValutazioneFiguraProfessionale());
        $chiusuraServizio = $schedaPAI->getIdChiusuraServizio();
        
        if ($numeroBarthelPresenti >= $numeroBarthelCorretto && $numeroBradenPresenti >= $numeroBradenCorretto && $numeroSpmsqPresenti >= $numeroSpmsqCorretto && $numeroTinettiPresenti >= $numeroTinettiCorretto && $numeroVasPresenti >= $numeroVasCorretto && $numeroLesioniPresenti >= $numeroLesioniCorretto && $numeroPainadPresenti >= $numeroPainadCorretto && $numeroCdrPresenti >= $numeroCdrCorretto && $chiusuraServizio != null && $numeroValutazioniProfessionali >= $numeroValutazioneProfessionaliMinime) {
            if ($schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura") {
                $schedaPAI->setCurrentPlace('chiusa');
                $session = $request->getSession();
                $session->set('alertSincronizzazione', 'chiusuraCompletata');
                $this->entityManager->flush();
            } else if ($schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo") {
                $schedaPAI->setCurrentPlace('chiusa_con_rinnovo');
                $session = $request->getSession();
                $session->set('alertSincronizzazione', 'chiusuraCompletata');
                $this->entityManager->flush();
            } else {
                $session = $request->getSession();
                $session->set('alertSincronizzazione', 'chiusuraFallitaPerStato');
                if ($pathName == 'app_scadenzario_index') {
                    return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()]);
                } else
                    return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()]);
            }
        } else {
            //alert fallimento chiusura
            $session = $request->getSession();
            $session->set('alertSincronizzazione', 'chiusuraFallita');
            if ($pathName == 'app_scadenzario_index') {
                return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()]);
            } else
                return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()]);
        }

        if ($pathName == 'app_scadenzario_index') {
            return $this->redirectToRoute('app_scadenzario_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        } else
            return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
    }

    #[Route('/sincronizza_progetti', name: 'app_scheda_pai_sincronizza', methods: ['GET'])]
    public function sincronizza(Request $request, SchedaPAIRepository $schedaPAIRepository)
    {
        $session = $request->getSession();

        $dataInizio = date('d-m-Y', strtotime('-3 month'));
        $dataFine = date('d-m-Y', strtotime('+3 month'));

        $this->SdManagerClientApiService->sincAssistiti();
        if ($this->SdManagerClientApiService->getCodiceResponseAssistiti() != 200) {
            $session->set('alertSincronizzazione', 'errore');
            return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        }
        $this->SdManagerClientApiService->sincOperatori();
        if ($this->SdManagerClientApiService->getCodiceResponseOperatori() != 200) {
            $session->set('alertSincronizzazione', 'errore');
            return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        }
        $this->SdManagerClientApiService->sincProgetti($dataInizio, $dataFine);
        if ($this->SdManagerClientApiService->getCodiceResponseProgetti() != 200) {
            $session->set('alertSincronizzazione', 'errore');
            return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
        }

        //libera la cache per recuperare dalla findby i nuovi dati
        $this->entityManager->clear();
        //ricalcolo del verifica dopo gli eventuali cambiamenti di data dei progetti
        $schedaPais = $schedaPAIRepository->findBy([]);
        for ($i = 0; $i < count($schedaPais); $i++) {
            $this->setterStatoVerificaSchedaPaiService->settaStatoVerifica($schedaPais[$i]);
        }
        $this->entityManager->flush();

        $session->set('alertSincronizzazione', 'completata');

        return $this->redirectToRoute('app_scheda_pai_index', ['page' => $request->query->get('page')], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/approva_scheda_pai/{id}', name: 'app_scheda_pai_approva', methods: ['GET'])]
    public function approva(Request $request, SchedaPAI $schedaPAI, string $pathName)
    {
        $this->denyAccessUnlessGranted('approva', $schedaPAI);

        $this->approvaSchedaService->approva($schedaPAI);
        if ($schedaPAI->getIdOperatorePrincipale() == null) {
            $session = $request->getSession();
            $session->set('alertSincronizzazione', 'approvazioneFallita');
        }

        return $this->redirectToRoute($pathName, ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/rinnova_scheda_pai/{id}', name: 'app_scheda_pai_rinnova', methods: ['GET'])]
    public function rinnova(Request $request, SchedaPAI $schedaPAI, string $pathName)
    {
        $this->denyAccessUnlessGranted('rinnovare', $schedaPAI);
        $schedaPAI->setCurrentPlace("in_attesa_di_chiusura_con_rinnovo");
        $this->entityManager->flush();
        
        if($schedaPAI->getIdChiusuraServizio() == null)
            return $this->redirectToRoute('app_chiusura_servizio_new',['page' => $request->query->get('page'), 'pathName' => $pathName, 'id_pai' => $schedaPAI->getId(), '_fragment' => $schedaPAI->getId()],Response::HTTP_SEE_OTHER);
        
        return $this->redirectToRoute($pathName, ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/non_rinnovare_scheda_pai/{id}', name: 'app_scheda_pai_non_rinnovare', methods: ['GET'])]
    public function nonRinnovare(Request $request, SchedaPAI $schedaPAI, string $pathName)
    {
        $this->denyAccessUnlessGranted('non_rinnovare', $schedaPAI);
        $schedaPAI->setCurrentPlace("in_attesa_di_chiusura");
        //il setter sistema il calcolo del totale scale
        $this->entityManager->flush();

        if($schedaPAI->getIdChiusuraServizio() == null)
            return $this->redirectToRoute('app_chiusura_servizio_new',['page' => $request->query->get('page'), 'pathName' => $pathName, 'id_pai' => $schedaPAI->getId(), '_fragment' => $schedaPAI->getId()],Response::HTTP_SEE_OTHER);
        
        return $this->redirectToRoute($pathName, ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/torna_al_verifica_scheda_pai/{id}', name: 'app_scheda_pai_torna_al_verifica', methods: ['GET'])]
    public function tornaInVerifica(Request $request, SchedaPAI $schedaPAI, string $pathName)
    {
        //il setter sistema il calcolo del totale scale
        $schedaPAI->setCurrentPlace("verifica");
        $this->entityManager->flush();

        return $this->redirectToRoute($pathName, ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{pathName}/invia_pdf_caregiver/{id}', name: 'app_scheda_pai_invia_pdf_caregiver', methods: ['GET'])]
    public function inviaPdfCaregiver(Request $request, SchedaPAI $schedaPAI, string $pathName)
    {
        $dataCreazione = date("d-m-Y");
        $assistito = $schedaPAI->getAssistito();
        $nomePdf = "Scheda-PAI-di-" . $assistito->getNome() . "-" . $assistito->getCognome() . "-" . $dataCreazione .".pdf";
        
        //creo e salvo il pdf
        $html = $this->creazionePdfSchedaPaiService->generaHtmlPerPdf($schedaPAI);
        $this->creazionePdfSchedaPaiService->salvaPdf($schedaPAI, $html);
        //invio pdf
        $this->mailerGenerator->EmailCaregiver($schedaPAI);
        //elimino il pdf
        $filesystem = new Filesystem();
        $filesystem->remove("/tmp/" . $nomePdf);

        return $this->redirectToRoute($pathName, ['page' => $request->query->get('page'), '_fragment' => $schedaPAI->getId()], Response::HTTP_SEE_OTHER);
    }
}
