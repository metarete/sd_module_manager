<?php

namespace App\Service;

use App\Entity\EntityPAI\Painad;
use Doctrine\ORM\EntityManagerInterface;

Class SetterTotaliPainadService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settaTotali(Painad $painad)
    {
        $respiro = $painad->getRespiro();
        $vocalizzazione = $painad->getVocalizzazione();
        $espressioneFacciale = $painad->getEspressioneFacciale();
        $linguaggioDelCorpo = $painad->getLinguaggioDelCorpo();
        $consolabilita = $painad->getConsolabilita();
        
        $totale = 0;       
        $totale = $respiro + $vocalizzazione + $espressioneFacciale + $linguaggioDelCorpo + $consolabilita;       
        $painad->setTotale($totale);
        $this->entityManager->flush();
    }
}
