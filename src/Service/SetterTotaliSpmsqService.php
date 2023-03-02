<?php

namespace App\Service;

use App\Entity\EntityPAI\SPMSQ;
use Doctrine\ORM\EntityManagerInterface;


Class SetterTotaliSpmsqService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function settaTotali(SPMSQ $spmsq)
    {
        $giornoOggi = $spmsq->isGiornoOggi();
        $giornoSettimana = $spmsq->isGiornoSettimana();
        $posto = $spmsq->isPosto();
        $indirizzo = $spmsq->isIndirizzo();
        $anni = $spmsq->isAnni();
        $dataNascita = $spmsq->isDataNascita();
        $presidenteAttuale = $spmsq->isPresidenteAttuale();
        $presidentePrecedente = $spmsq->isPresidentePrecedente();
        $cognomeMadre = $spmsq->isCognomeMadre();
        $sottrazione = $spmsq->isSottrazione();
       
        $totale = $giornoOggi+$giornoSettimana+$posto+$indirizzo+$anni+$dataNascita+$presidenteAttuale+$presidentePrecedente+$cognomeMadre+$sottrazione;
        $spmsq->setTotale($totale);
        $this->entityManager->flush();
    }
}