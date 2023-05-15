<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\ORM\EntityManagerInterface;

class SetterValoriNonMappatiScaleSchedaPaiService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settaValoriScale(SchedaPAI $schedaPAI)
    {
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

        
    }
}