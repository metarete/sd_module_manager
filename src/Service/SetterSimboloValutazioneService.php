<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;

class SetterSimboloValutazioneService
{
    const PRESENTE = 'bi bi-check-circle-fill';
    const NON_PRESENTE = 'bi bi-x-circle-fill';
    const NON_DISPONIBILE = 'bi bi-dash-circle-fill';
    const VERDE = 'color:green';
    const ROSSO = 'color:red';
    const GRIGIO = 'color:grey';

    public function settaSimboloValutazione(SchedaPAI $schedaPAI):string
    {
        $simbolo = null;

        if($schedaPAI->getCurrentPlace() == 'nuova' || $schedaPAI->getCurrentPlace() == 'approvata'){
            $simbolo = self::NON_DISPONIBILE;
        }
        else{
            if(count($schedaPAI->getIdValutazioneFiguraProfessionale()) > 0){
                $simbolo = self::PRESENTE;
            }
            else{
                $simbolo = self::NON_PRESENTE;
            }
        }

        return $simbolo;
    }

    public function settaColoreValutazione(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->getCurrentPlace() == 'nuova' || $schedaPAI->getCurrentPlace() == 'approvata'){
            $colore = self::GRIGIO;
        }
        else{
            if(count($schedaPAI->getIdValutazioneFiguraProfessionale()) > 0){
                $colore = self::VERDE;
            }
            else{
                $colore = self::ROSSO;
            }
        }

        return $colore;
    }
}