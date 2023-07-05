<?php

namespace App\Controller\ControllerPAI;

use App\Entity\EntityPAI\Pratica;
use App\Service\AltraTipologiaAssistenzaService;
use App\Service\BisogniService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pratica')]
class PraticaController extends AbstractController
{

    private $altraTipologiaAssistenzaService;
    private $bisogniService;

    public function __construct(AltraTipologiaAssistenzaService $altraTipologiaAssistenzaService, BisogniService $bisogniService)
    {
        $this->altraTipologiaAssistenzaService = $altraTipologiaAssistenzaService;
        $this->bisogniService = $bisogniService;
    }

    #[Route('/{id}', name: 'app_pratica_show', methods: ['GET'])]
    public function show(Pratica $pratica): Response
    {
        $schedePai = $pratica->getSchedaPais();
        $bisogni = [];
        $altraTipologiaAssistenza = [];
        $valutazioniProfessionali = [];
        $pareriMmg = [];
        $valutazioniGenerali = [];
        $barthel = [];
        $braden = [];
        $spmsq = [];
        $tinetti = [];
        $vas = [];
        $lesioni = [];
        $painad = [];
        $cdr = [];

        for($i=0; $i<count($schedePai); $i++){
            //costruisco array di bisogni e tipologie assistenza
            $a = $this->altraTipologiaAssistenzaService->getAltreTipologieAssistenza($schedePai[$i]->getIdValutazioneGenerale());
            if($schedePai[$i]->getIdValutazioneGenerale() != null || $schedePai[$i]->getIdValutazioneGenerale() != [])
                array_push($altraTipologiaAssistenza, $a);
            $b = $this->bisogniService->getBisogni($schedePai[$i]->getIdValutazioneGenerale());
            if($schedePai[$i]->getIdValutazioneGenerale() != null || $schedePai[$i]->getIdValutazioneGenerale() != [])
                array_push($bisogni, $b);

            //costruisco l'array delle valutazioni generali
            $vg = $schedePai[$i]->getIdValutazioneGenerale();
            if($vg != null)
                array_push($valutazioniGenerali, $vg);
            
            //costruisco l'array di valutazioni professionali
            $vp = $schedePai[$i]->getIdValutazioneFiguraProfessionale();
            for($j=0; $j<count($vp); $j++){
                array_push($valutazioniProfessionali, $vp[$j]);
            }

            //costruisco l'array dei pareri del medico
            $p = $schedePai[$i]->getIdParereMmg();
            if($p != null)
                array_push($pareriMmg, $p);

            //costruisco l'array delle barthel
            $bart = $schedePai[$i]->getIdBarthel();
            for($j=0; $j<count($bart); $j++){
                array_push($barthel, $bart[$j]);
            }

            //costruisco l'array delle braden
            $bra = $schedePai[$i]->getIdBraden();
            for($j=0; $j<count($bra); $j++){
                array_push($braden, $bra[$j]);
            }

            //costruisco l'array delle spmsq
            $sp = $schedePai[$i]->getIdSpmsq();
            for($j=0; $j<count($sp); $j++){
                array_push($spmsq, $sp[$j]);
            }

            //costruisco l'array delle tinetti
            $tin = $schedePai[$i]->getIdTinetti();
            for($j=0; $j<count($tin); $j++){
                array_push($tinetti, $tin[$j]);
            }

            //costruisco l'array delle vas
            $va = $schedePai[$i]->getIdVas();
            for($j=0; $j<count($va); $j++){
                array_push($vas, $va[$j]);
            }

            //costruisco l'array delle lesioni
            $les = $schedePai[$i]->getIdLesioni();
            for($j=0; $j<count($les); $j++){
                array_push($lesioni, $les[$j]);
            }

            //costruisco l'array delle painad
            $pai = $schedePai[$i]->getIdPainad();
            for($j=0; $j<count($pai); $j++){
                array_push($painad, $pai[$j]);
            }

            //costruisco l'array delle cdr
            $cd = $schedePai[$i]->getCdrs();
            for($j=0; $j<count($cd); $j++){
                array_push($cdr, $cd[$j]);
            }
        }
        
        
        return $this->render('pratica/show.html.twig', [
            'pratica' => $pratica,
            'bisogni' => $bisogni,
            'altraTipologiaAssistenza' => $altraTipologiaAssistenza,
            'schedePai' => $schedePai,
            'valutazioniProfessionali' => $valutazioniProfessionali,
            'pareriMmg' => $pareriMmg,
            'valutazioniGenerali' => $valutazioniGenerali,
            'barthels' => $barthel,
            'bradens' => $braden,
            'spmsqs' => $spmsq,
            'tinettis' => $tinetti,
            'vass' => $vas,
            'lesionis' => $lesioni,
            'painads' => $painad,
            'cdrs' => $cdr,
        ]);
    }
}
