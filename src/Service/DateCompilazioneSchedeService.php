<?php

namespace App\Service;

use DateTime;
use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\ORM\EntityManagerInterface;


class DateCompilazioneSchedeService
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settaScadenzarioSchede(SchedaPAI $schedaPAI)
    {
        $this->settaScadenzarioBarthel($schedaPAI);
        $this->settaScadenzarioBraden($schedaPAI);
        $this->settaScadenzarioSpmsq($schedaPAI);
        $this->settaScadenzarioTinetti($schedaPAI);
        $this->settaScadenzarioVas($schedaPAI);
        $this->settaScadenzarioLesioni($schedaPAI);

    }
    public function settaScadenzarioBarthel(SchedaPAI $schedaPAI)
    {
        $em = $this->entityManager;
        $attivazioneBarthel = $schedaPAI->isAbilitaBarthel();
        $frequenzaBarthel = $schedaPAI->getFrequenzaBarthel();
        $numeroBarthelPresentiOggi = count($schedaPAI->getIdBarthel());
        
        if($attivazioneBarthel == false){
            return null;
        }

        elseif( $frequenzaBarthel == 0){
            return null;
        }
        
        $dataInizio = $schedaPAI->getDataInizio();
        $dataOggi = new DateTime();
        $numeroGiorniAdOggi = $dataOggi->diff($dataInizio)->days;
        $numeroBarthelAdOggiCorretto = (int)($numeroGiorniAdOggi / $frequenzaBarthel);
        $schedaPAI->setNumeroBarthelAdOggi($numeroBarthelPresentiOggi);
        $schedaPAI->setNumeroBarthelAdOggiCorretto($numeroBarthelAdOggiCorretto);
        $em->flush();
    }
    public function settaScadenzarioBraden(SchedaPAI $schedaPAI)
    {
        $em = $this->entityManager;
        $attivazioneBraden = $schedaPAI->isAbilitaBraden();
        $frequenzaBraden = $schedaPAI->getFrequenzaBraden();
        $numeroBradenPresentiOggi = count($schedaPAI->getIdBraden());
        
        if( $attivazioneBraden == false){
            return null;
        }
        elseif($frequenzaBraden == 0){
            return null;
        }
        
        $dataInizio = $schedaPAI->getDataInizio();
        $dataOggi = new DateTime();
        $numeroGiorniAdOggi = $dataOggi->diff($dataInizio)->days;
        $numeroBradenAdOggiCorretto = (int)($numeroGiorniAdOggi / $frequenzaBraden);
        $schedaPAI->setNumeroBradenAdOggi($numeroBradenPresentiOggi);
        $schedaPAI->setNumeroBradenAdOggiCorretto($numeroBradenAdOggiCorretto);
        $em->flush();
    }
    public function settaScadenzarioSpmsq(SchedaPAI $schedaPAI)
    {
        $em = $this->entityManager;
        $attivazioneSpmsq = $schedaPAI->isAbilitaSpmsq();
        $frequenzaSpmsq = $schedaPAI->getFrequenzaSpmsq();
        $numeroSpmsqPresentiOggi = count($schedaPAI->getIdSpmsq());
        
        if( $attivazioneSpmsq == false ){
            return null;
        }
        elseif($frequenzaSpmsq == 0){
            return null;
        }
        
        $dataInizio = $schedaPAI->getDataInizio();
        $dataOggi = new DateTime();
        $numeroGiorniAdOggi = $dataOggi->diff($dataInizio)->days;
        $numeroSpmsqAdOggiCorretto = (int)($numeroGiorniAdOggi / $frequenzaSpmsq);
        $schedaPAI->setNumeroSpmsqAdOggi($numeroSpmsqPresentiOggi);
        $schedaPAI->setNumeroSpmsqAdOggiCorretto($numeroSpmsqAdOggiCorretto);
        $em->flush();
    }
    public function settaScadenzarioTinetti(SchedaPAI $schedaPAI)
    {
        
        $em = $this->entityManager;
        $attivazioneTinetti = $schedaPAI->isAbilitaTinetti();
        $frequenzaTinetti = $schedaPAI->getFrequenzaTinetti();
        $numeroTinettiPresentiOggi = count($schedaPAI->getIdTinetti());
        
        if( $attivazioneTinetti == false ){
            return null;
        }
        elseif($frequenzaTinetti == 0){
            return null;
        }
        
        $dataInizio = $schedaPAI->getDataInizio();
        $dataOggi = new DateTime();
        $dataOggi->format('Y-m-d');
        $numeroGiorniAdOggi = $dataOggi->diff($dataInizio)->days;
        $numeroTinettiAdOggiCorretto = (int)($numeroGiorniAdOggi / $frequenzaTinetti);
        $schedaPAI->setNumeroTinettiAdOggi($numeroTinettiPresentiOggi);
        $schedaPAI->setNumeroTinettiAdOggiCorretto($numeroTinettiAdOggiCorretto);
        $em->flush();
    }
    public function settaScadenzarioVas(SchedaPAI $schedaPAI)
    {
        $em = $this->entityManager;
        $attivazioneVas = $schedaPAI->isAbilitaVas();
        $frequenzaVas = $schedaPAI->getFrequenzaVas();
        $numeroVasPresentiOggi = count($schedaPAI->getIdVas());
        
        if( $attivazioneVas == false ){
            return null;
        }
        elseif($frequenzaVas == 0){
            return null;
        }
        
        $dataInizio = $schedaPAI->getDataInizio();
        $dataOggi = new DateTime();
        $numeroGiorniAdOggi = $dataOggi->diff($dataInizio)->days;
        $numeroVasAdOggiCorretto = (int)($numeroGiorniAdOggi / $frequenzaVas);
        $schedaPAI->setNumeroVasAdOggi($numeroVasPresentiOggi);
        $schedaPAI->setNumeroVasAdOggiCorretto($numeroVasAdOggiCorretto);
        $em->flush();
    }
    public function settaScadenzarioLesioni(SchedaPAI $schedaPAI)
    {
        $em = $this->entityManager;
        $attivazioneLesioni = $schedaPAI->isAbilitaLesioni();
        $frequenzaLesioni = $schedaPAI->getFrequenzaLesioni();
        $numeroLesioniPresentiOggi = count($schedaPAI->getIdLesioni());
        
        if( $attivazioneLesioni == false ){
            return null;
        }
        elseif($frequenzaLesioni == 0){
            return null;
        }
        
        $dataInizio = $schedaPAI->getDataInizio();
        $dataOggi = new DateTime();
        $numeroGiorniAdOggi = $dataOggi->diff($dataInizio)->days;
        $numeroLesioniAdOggiCorretto = (int)($numeroGiorniAdOggi / $frequenzaLesioni);
        $schedaPAI->setNumeroLesioniAdOggi($numeroLesioniPresentiOggi);
        $schedaPAI->setNumeroLesioniAdOggiCorretto($numeroLesioniAdOggiCorretto);
        $em->flush();
    }
    
}