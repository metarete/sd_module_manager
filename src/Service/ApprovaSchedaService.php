<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\ORM\EntityManagerInterface;



class ApprovaSchedaService
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function approva(SchedaPAI $schedaPai)
    {
        if($schedaPai->getIdOperatorePrincipale()== null){
            return;
        }
        else{
            $schedaPai->setCurrentPlace('approvata');
        }
        $this->entityManager->flush();
        
    }
}