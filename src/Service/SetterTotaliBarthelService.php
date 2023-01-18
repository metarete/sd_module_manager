<?php

namespace App\Service;

use App\Entity\EntityPAI\Barthel;
use Doctrine\ORM\EntityManagerInterface;

Class SetterTotaliBarthelService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settaTotali(Barthel $barthel)
    {
        $alimentazione = $barthel->getAlimentazione();
        $bagnoDoccia = $barthel->getBagnoDoccia();
        $igienePersonale = $barthel->getIgienePersonale();
        $abbigliamento = $barthel->getAbbigliamento();
        $continenzaIntestinale = $barthel->getContinenzaIntestinale();
        $continenzaUrinaria = $barthel->getContinenzaUrinaria();
        $toilet = $barthel->getToilet();
        $totaleValutazioneFunzionale = $alimentazione + $bagnoDoccia + $igienePersonale + $abbigliamento + $continenzaIntestinale + $continenzaUrinaria + $toilet;
        $trasferimentoLettoSedia = $barthel->getTrasferimentoLettoSedia();
        $scale = $barthel->getScale();
        $deambulazioneValida = $barthel->getDeambulazioneValida();
        $usoCarrozzina = $barthel->getUsoCarrozzina();
        $deambulazione = $barthel->isDeambulazione();
        $totale = 0;
        if($deambulazione == true){
            $totale = $totaleValutazioneFunzionale + $trasferimentoLettoSedia + $scale + $deambulazioneValida;
        }
        else{
            $totale = $totaleValutazioneFunzionale + $trasferimentoLettoSedia + $scale + $usoCarrozzina;
        }
        $barthel->setTotaleValutazioneFunzionale($totaleValutazioneFunzionale);
        $barthel->setTotale($totale);
        $this->entityManager->flush();
    }
}
