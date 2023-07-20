<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use DateTime;
use Symfony\Component\Workflow\WorkflowInterface;

class SetterStatoVerificaSchedaPaiService
{
    private $workflow;

    public function __construct(WorkflowInterface $schedePaiCreatingStateMachine)
    {
        $this->workflow = $schedePaiCreatingStateMachine;
    }

    public function settaStatoVerifica(SchedaPAI $schedaPAI)
    {
        $dataOggi = new DateTime('now');
        $diff = date_diff($dataOggi, $schedaPAI->getDataFine());

        if($diff->days <= 7){
            if($schedaPAI->getCurrentPlace() == 'attiva'){
                $this->workflow->apply($schedaPAI, 'verifica');
            }         
        }
    }
}