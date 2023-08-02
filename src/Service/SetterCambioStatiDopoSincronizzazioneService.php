<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class SetterCambioStatiDopoSincronizzazioneService
{
    private $workflow;
    private $entityManager;

    public function __construct(WorkflowInterface $schedePaiCreatingStateMachine, EntityManagerInterface $entityManager)
    {
        $this->workflow = $schedePaiCreatingStateMachine;
        $this->entityManager = $entityManager;
    }

    public function settaCambioStati(DateTime $dataFineNuova, SchedaPAI $schedaPAI)
    {
        //spostamento della data di fine in avanti
        if(($dataFineNuova->format('d-m-Y') > $schedaPAI->getDataFine()->format('d-m-Y'))){
            if($schedaPAI->getCurrentPlace()=='verifica'){
                $this->workflow->apply($schedaPAI, 'attiva_per_cambio_dati3');                
            }
            elseif($schedaPAI->getCurrentPlace()=='in_attesa_di_chiusura'){
                $this->workflow->apply($schedaPAI, 'attiva_per_cambio_dati1');
            }
            elseif($schedaPAI->getCurrentPlace()=='in_attesa_di_chiusura_con_rinnovo'){
                $this->workflow->apply($schedaPAI, 'attiva_per_cambio_dati2');
            }
            else{
                //nulla
            }
        }
        else{
            //non cambia nulla
        }
        $this->entityManager->flush();

    }
}