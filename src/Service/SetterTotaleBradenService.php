<?php

namespace App\Service;

use App\Entity\EntityPAI\Braden;
use Doctrine\ORM\EntityManagerInterface;

Class SetterTotaleBradenService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settaTotale(Braden $braden)
    {
        $percezioneSensoriale = $braden->getPercezioneSensoriale();
        $umidita = $braden->getUmidita();
        $attivita = $braden->getAttivita();
        $mobilita = $braden->getMobilita();
        $nutrizione = $braden->getNutrizione();
        $frizioneScivolamento = $braden->getFrizioneScivolamento();
        $totale = $percezioneSensoriale+$umidita+$attivita+$mobilita+$nutrizione+$frizioneScivolamento;
        $braden->setTotale($totale);
        $this->entityManager->flush();
    }
}
