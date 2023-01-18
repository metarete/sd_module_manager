<?php

namespace App\Service;

use App\Entity\EntityPAI\Tinetti;
use Doctrine\ORM\EntityManagerInterface;


Class SetterTotaliTinettiService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settaTotali(Tinetti $tinetti)
    {
        $equilibrioSeduto = $tinetti->getEquilibrioSeduto();
        $sedia = $tinetti->getSedia();
        $alzarsi = $tinetti->getAlzarsi();
        $stazioneEretta = $tinetti->getStazioneEretta();
        $stazioneErettaProlungata = $tinetti->getStazioneErettaProlungata();
        $romberg = $tinetti->getRomberg();
        $rombergSensibilizzato = $tinetti->getRomberg();
        $girarsi1 = $tinetti->getGirarsi1();
        $girarsi2 = $tinetti->getGirarsi2();
        $sedersi = $tinetti->getSedersi();
        $totaleEquilibrio = $equilibrioSeduto+$sedia+$alzarsi+$stazioneEretta+$stazioneErettaProlungata+$romberg+$rombergSensibilizzato+$girarsi1+$girarsi2+$sedersi;
        $tinetti->setTotaleEquilibrio($totaleEquilibrio);
        $inizioDeambulazione = $tinetti->getInizioDeambulazione();
        $piedeDx = $tinetti->getPiedeDx();
        $piedeDx2 = $tinetti->getPiedeDx2();
        $piedeSx = $tinetti->getPiedeSx();
        $piedeSx2 = $tinetti->getPiedeSx2();
        $simmetriaPasso = $tinetti->getSimmetriaPasso();
        $continuitaPasso = $tinetti->getContinuitaPasso();
        $traiettoria = $tinetti->getTraiettoria();
        $tronco = $tinetti->getTronco();
        $cammino = $tinetti->getCammino();
        $totaleAndatura = $inizioDeambulazione+$piedeDx+$piedeDx2+$piedeSx+$piedeSx2+$simmetriaPasso+$continuitaPasso+$traiettoria+$tronco+$cammino;
        $tinetti->setTotaleAndatura($totaleAndatura);
        $totale = $totaleEquilibrio+$totaleAndatura;
        $tinetti->setTotale($totale);
        $this->entityManager->flush();
    }
}