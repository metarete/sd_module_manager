<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\Paziente;
use Doctrine\ORM\EntityManagerInterface;

Class SetterDatiSchedaPaiService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settaDatiAssistito(SchedaPAI $schedaPAI)
    {
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        $this->entityManager->flush();
    }

    public function settaDatiCompilazioneSchede(SchedaPAI $schedaPAI)
    {
        //non mappati
        $schedaPAI->setBarthelNumberToday();
        $schedaPAI->setCorrectBarthelNumberToday();
        $schedaPAI->setBradenNumberToday();
        $schedaPAI->setCorrectBradenNumberToday();
        $schedaPAI->setSpmsqNumberToday();
        $schedaPAI->setCorrectSpmsqNumberToday();
        $schedaPAI->setTinettiNumberToday();
        $schedaPAI->setCorrectTinettiNumberToday();
        $schedaPAI->setVasNumberToday();
        $schedaPAI->setCorrectVasNumberToday();
        $schedaPAI->setLesioniNumberToday();
        $schedaPAI->setCorrectLesioniNumberToday();
        $schedaPAI->setPainadNumberToday();
        $schedaPAI->setCorrectPainadNumberToday();
        $schedaPAI->setCdrNumberToday();
        $schedaPAI->setCorrectCdrNumberToday();

        //mappati
        $frequenzaBarthel = $schedaPAI->getFrequenzaBarthel();
        $frequenzaBraden = $schedaPAI->getFrequenzaBraden();
        $frequenzaSpmsq = $schedaPAI->getFrequenzaSpmsq();
        $frequenzaTinetti = $schedaPAI->getFrequenzaTinetti();
        $frequenzaVas = $schedaPAI->getFrequenzaVas();
        $frequenzaLesioni = $schedaPAI->getFrequenzaLesioni();
        $frequenzaPainad = $schedaPAI->getFrequenzaPainad();
        $frequenzaCdr = $schedaPAI->getFrequenzaCdr();
        $dataInizio = $schedaPAI->getDataInizio();
        $dataFine = $schedaPAI->getDataFine();
        $numeroGiorniTotali = $dataFine->diff($dataInizio)->days;

        //Barthel
        if ($frequenzaBarthel == 0) {
            $numeroBarthelCorretto = 0;
        } else
            $numeroBarthelCorretto = (int)($numeroGiorniTotali / $frequenzaBarthel) +1;

        //Braden
        if ($frequenzaBraden == 0) {
            $numeroBradenCorretto = 0;
        } else
            $numeroBradenCorretto = (int)($numeroGiorniTotali / $frequenzaBraden) +1;

        //SPMSQ
        if ($frequenzaSpmsq == 0) {
            $numeroSpmsqCorretto = 0;
        } else
            $numeroSpmsqCorretto = (int)($numeroGiorniTotali / $frequenzaSpmsq) +1;

        //Tinetti
        if ($frequenzaTinetti == 0) {
            $numeroTinettiCorretto = 0;
        } else
            $numeroTinettiCorretto = (int)($numeroGiorniTotali / $frequenzaTinetti) +1;

        //VAS
        if ($frequenzaVas == 0) {
            $numeroVasCorretto = 0;
        } else
            $numeroVasCorretto = (int)($numeroGiorniTotali / $frequenzaVas) +1;

        //Lesioni
        if ($frequenzaLesioni == 0) {
            $numeroLesioniCorretto = 0;
        } else
            $numeroLesioniCorretto = (int)($numeroGiorniTotali / $frequenzaLesioni) +1;

        //Painad
        if ($frequenzaPainad == 0) {
            $numeroPainadCorretto = 0;
        } else
            $numeroPainadCorretto = (int)($numeroGiorniTotali / $frequenzaPainad) +1;
        
        //CDR
        if ($frequenzaCdr == 0) {
            $numeroCdrCorretto = 0;
        } else
            $numeroCdrCorretto = (int)($numeroGiorniTotali / $frequenzaCdr) +1;

        $schedaPAI->setNumeroBarthelCorretto($numeroBarthelCorretto);
        $schedaPAI->setNumeroBradenCorretto($numeroBradenCorretto);
        $schedaPAI->setNumeroSpmsqCorretto($numeroSpmsqCorretto);
        $schedaPAI->setNumeroTinettiCorretto($numeroTinettiCorretto);
        $schedaPAI->setNumeroVasCorretto($numeroVasCorretto);
        $schedaPAI->setNumeroLesioniCorretto($numeroLesioniCorretto);
        $schedaPAI->setNumeroPainadCorretto($numeroPainadCorretto);
        $schedaPAI->setNumeroCdrCorretto($numeroCdrCorretto);

        //se non rinnovo la scheda aggiungo una valutazione finale per ogni scala

        if($schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == 'chiusa'){
            if($schedaPAI->isAbilitaBarthel()== true){
                $numeroBarthelCorretto = $schedaPAI->getNumeroBarthelCorretto()+1;
                $schedaPAI->setNumeroBarthelCorretto($numeroBarthelCorretto);
            }
            if($schedaPAI->isAbilitaBraden()== true){
                $numeroBradenCorretto = $schedaPAI->getNumeroBradenCorretto()+1;
                $schedaPAI->setNumeroBradenCorretto($numeroBradenCorretto);
            }
            if($schedaPAI->isAbilitaSpmsq()== true){
                $numeroSpmsqCorretto = $schedaPAI->getNumeroSpmsqCorretto()+1;
                $schedaPAI->setNumeroSpmsqCorretto($numeroSpmsqCorretto);
            }
            if($schedaPAI->isAbilitaTinetti()== true){
                $numeroTinettiCorretto = $schedaPAI->getNumeroTinettiCorretto()+1;
                $schedaPAI->setNumeroTinettiCorretto($numeroTinettiCorretto);
            }
            if($schedaPAI->isAbilitaVas()== true){
                $numeroVasCorretto = $schedaPAI->getNumeroVasCorretto()+1;
                $schedaPAI->setNumeroVasCorretto($numeroVasCorretto);
            }
            if($schedaPAI->isAbilitaLesioni()== true){
                $numeroLesioniCorretto = $schedaPAI->getNumeroLesioniCorretto()+1;
                $schedaPAI->setNumeroLesioniCorretto($numeroLesioniCorretto);
            }
            if($schedaPAI->isAbilitaPainad()== true){
                $numeroPainadCorretto = $schedaPAI->getNumeroPainadCorretto()+1;
                $schedaPAI->setNumeroPainadCorretto($numeroPainadCorretto);
            }
            if($schedaPAI->isAbilitaCdr()== true){
                $numeroCdrCorretto = $schedaPAI->getNumeroCdrCorretto()+1;
                $schedaPAI->setNumeroCdrCorretto($numeroCdrCorretto);
            }
        }

        // reset valori ritardi in caso di chiusura forzata

        if($schedaPAI->getCurrentPlace() == "chiusura_forzata"){
            if($schedaPAI->isAbilitaBarthel()== true){
                $numeroBarthelCorretto = $schedaPAI->getBarthelNumberToday();
                $schedaPAI->setNumeroBarthelCorretto($numeroBarthelCorretto);
            }
            if($schedaPAI->isAbilitaBraden()== true){
                $numeroBradenCorretto = $schedaPAI->getBradenNumberToday();
                $schedaPAI->setNumeroBradenCorretto($numeroBradenCorretto);
            }
            if($schedaPAI->isAbilitaSpmsq()== true){
                $numeroSpmsqCorretto = $schedaPAI->getSpmsqNumberToday();
                $schedaPAI->setNumeroSpmsqCorretto($numeroSpmsqCorretto);
            }
            if($schedaPAI->isAbilitaTinetti()== true){
                $numeroTinettiCorretto = $schedaPAI->getTinettiNumberToday();
                $schedaPAI->setNumeroTinettiCorretto($numeroTinettiCorretto);
            }
            if($schedaPAI->isAbilitaVas()== true){
                $numeroVasCorretto = $schedaPAI->getVasNumberToday();
                $schedaPAI->setNumeroVasCorretto($numeroVasCorretto);
            }
            if($schedaPAI->isAbilitaLesioni()== true){
                $numeroLesioniCorretto = $schedaPAI->getLesioniNumberToday();
                $schedaPAI->setNumeroLesioniCorretto($numeroLesioniCorretto);
            }
            if($schedaPAI->isAbilitaPainad()== true){
                $numeroPainadCorretto = $schedaPAI->getPainadNumberToday();
                $schedaPAI->setNumeroPainadCorretto($numeroPainadCorretto);
            }
            if($schedaPAI->isAbilitaCdr()== true){
                $numeroCdrCorretto = $schedaPAI->getCdrNumberToday();
                $schedaPAI->setNumeroCdrCorretto($numeroCdrCorretto);
            }
        }
    }

}
