<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;

class SetterNomiStatoSchedaPaiService
{
    const PINK = 'bg-pink';
    const SUCCESS = 'bg-success';
    const BROWN = 'bg-brown';
    const PURPLE = 'bg-purple';
    const WARNING = 'bg-warning';
    const BLUE = 'bg-blue';
    const DANGER = 'bg-danger';
    const ORANGE= 'bg-orange';
    const DARKRED= 'bg-darkred';
    
    public function settaNomeStato(SchedaPAI $schedaPai ) :string
    {
        $nomeStato = null;
        if ($schedaPai->getCurrentPlace() == 'approvata')
            $nomeStato = 'Approvata';
        elseif ($schedaPai->getCurrentPlace() == 'attiva')
            $nomeStato = 'Attiva';
        elseif ($schedaPai->getCurrentPlace() == 'nuova')
            $nomeStato = 'Nuova';
        elseif ($schedaPai->getCurrentPlace() == 'verifica')
            $nomeStato = 'Verifica';
        elseif ($schedaPai->getCurrentPlace() == 'in_attesa_di_chiusura')
            $nomeStato = 'In Attesa Di Chiusura';
        elseif ($schedaPai->getCurrentPlace() =='in_attesa_di_chiusura_con_rinnovo')
            $nomeStato = 'In Attesa Di Chiusura Con Rinnovo';
        elseif ($schedaPai->getCurrentPlace() == 'chiusa')
            $nomeStato = 'Chiusa';
        elseif ($schedaPai->getCurrentPlace() == 'chiusa_con_rinnovo')
            $nomeStato = 'Chiusa Con rinnovo';
        elseif ($schedaPai->getCurrentPlace() == 'chiusura_forzata')
            $nomeStato = 'Chiusura Forzata';
        else
            $nomeStato = 'Nessuno';

        return $nomeStato;
    }

    public function settaColoreBadge(SchedaPAI $schedaPAI):string
    {
        $coloreBadge = null;

        if ($schedaPAI->getCurrentPlace() == 'approvata') 
            $coloreBadge = self::PINK;
        elseif ($schedaPAI->getCurrentPlace() == 'attiva')
            $coloreBadge = self::SUCCESS;
        elseif ($schedaPAI->getCurrentPlace() == 'verifica')
            $coloreBadge = self::BROWN;
        elseif ($schedaPAI->getCurrentPlace() == 'nuova')
            $coloreBadge = self::PURPLE;
        elseif ($schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura') 
            $coloreBadge = self::WARNING;
        elseif ($schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura_con_rinnovo') 
            $coloreBadge = self::BLUE;
        elseif ($schedaPAI->getCurrentPlace() == 'chiusa')
            $coloreBadge = self::DANGER;
        elseif ($schedaPAI->getCurrentPlace() == 'chiusa_con_rinnovo') 
            $coloreBadge = self::ORANGE;
        elseif ($schedaPAI->getCurrentPlace() == 'chiusura_forzata') 
            $coloreBadge = self::DARKRED;
        else
            $coloreBadge = self::DANGER;

        return $coloreBadge;
    }

    public function settaColoriStatoSDManager(SchedaPAI $schedaPAI):string
    {
        $coloreStato = null;

        if ($schedaPAI->getStatoSDManager() == 'ATTIVO') 
            $coloreStato = self::SUCCESS;
        elseif ($schedaPAI->getStatoSDManager() == 'NON ATTIVO')
            $coloreStato = self::ORANGE;
        elseif ($schedaPAI->getStatoSDManager() == 'SOSPESO')
            $coloreStato = self::ORANGE;
        elseif ($schedaPAI->getStatoSDManager() == 'CHIUSO')
            $coloreStato = self::DANGER;
        else
            $coloreStato = self::DANGER;

        return $coloreStato;
    }

}