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

    public function settaDati(SchedaPAI $schedaPAI)
    {
        $em = $this->entityManager;
        $assistitiRepository = $em->getRepository(Paziente::class);
        $nomeAssistito = $assistitiRepository->getNameById($schedaPAI->getIdAssistito());
        $cognomeAssistito = $assistitiRepository->getSurnameById($schedaPAI->getIdAssistito());
        $schedaPAI->setNomeAssistito($nomeAssistito);
        $schedaPAI->setCognomeAssistito($cognomeAssistito);
        $em->flush();
    }
}