<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;

class SetterDropdownScadenzarioService
{
    //style="display:none"> dentro <li>
    public function configura(SchedaPAI $schedaPai, User $user):string
    {
        $style = null;

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            $style = '';
        } else {
            if ($user->getUsername() == $schedaPai->getIdOperatorePrincipale()->getUsername()) {
                $style = '';
            } else {
                $style = 'display:none';
            }
        }
        return $style;
    }

    public function approva(SchedaPAI $schedaPai, User $user):string
    {
        if($schedaPai->getCurrentPlace() == 'nuova')
            return $this->configura($schedaPai, $user);
        else
            return 'display:none';
    }

    public function delete(SchedaPAI $schedaPai, User $user):string
    {
        return $this->configura($schedaPai, $user);
    }

    public function chiudi(SchedaPAI $schedaPAI, User $user):string
    {
        $style = null;

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            if ($schedaPAI->getCurrentPlace() == 'chiusa' || $schedaPAI->getCurrentPlace() == 'chiusa_con_rinnovo' || $schedaPAI->getCurrentPlace() == 'nuova' || $schedaPAI->getCurrentPlace() == 'approvata' || $schedaPAI->getCurrentPlace() == 'attiva' || $schedaPAI->getCurrentPlace() == 'verifica' || $schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura_con_rinnovo')
                $style = 'display:none';
            else
                $style = '';
        }else{
            if ($user->getUsername() == $schedaPAI->getIdOperatorePrincipale()->getUsername()){
                if ($schedaPAI->getCurrentPlace() == 'chiusa' || $schedaPAI->getCurrentPlace() == 'chiusa_con_rinnovo' || $schedaPAI->getCurrentPlace() == 'nuova' || $schedaPAI->getCurrentPlace() == 'approvata' || $schedaPAI->getCurrentPlace() == 'attiva' || $schedaPAI->getCurrentPlace() == 'verifica' || $schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura_con_rinnovo' )
                    $style = 'display:none';
                else
                    $style = '';
            }
            else{
                $style = 'display:none';
            }
        }
        return $style;  
    }

    public function chiudiConRinnovo(SchedaPAI $schedaPAI, User $user):string
    {
        $style = null;

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            if ($schedaPAI->getCurrentPlace() == 'chiusa' || $schedaPAI->getCurrentPlace() == 'chiusa_con_rinnovo' || $schedaPAI->getCurrentPlace() == 'nuova' || $schedaPAI->getCurrentPlace() == 'approvata' || $schedaPAI->getCurrentPlace() == 'attiva' || $schedaPAI->getCurrentPlace() == 'verifica' || $schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura')
                $style = 'display:none';
            else
                $style = '';
        }else{
            if ($user->getUsername() == $schedaPAI->getIdOperatorePrincipale()->getUsername()){
                if ($schedaPAI->getCurrentPlace() == 'chiusa' || $schedaPAI->getCurrentPlace() == 'chiusa_con_rinnovo' || $schedaPAI->getCurrentPlace() == 'nuova' || $schedaPAI->getCurrentPlace() == 'approvata' || $schedaPAI->getCurrentPlace() == 'attiva' || $schedaPAI->getCurrentPlace() == 'verifica' || $schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura')
                    $style = 'display:none';
                else
                    $style = '';
            }
            else{
                $style = 'display:none';
            }
        }
        return $style;  
    }

    public function rinnova(SchedaPAI $schedaPAI, User $user):string
    {
        $style = null;

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            if ($schedaPAI->getCurrentPlace() != 'verifica'){
                $style = 'display:none';
            } else {
                $style = ''; 
            }
        } else {
            if ($user->getUsername() == $schedaPAI->getIdOperatorePrincipale()->getUsername()){
                if ($schedaPAI->getCurrentPlace() != 'verifica'){
                    $style = 'display:none';
                } else {
                    $style = ''; 
                }
            } else{
                $style = 'display:none';
            }
        }
        return $style;
    }

    public function tornaAlVerifica(SchedaPAI $schedaPAI, User $user):string
    {
        $style = null;

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            if ($schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura' || $schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura_con_rinnovo'){
                $style = '';
            }else {
                $style = 'display:none';                          
            }
        } else {
            if ($user->getUsername() == $schedaPAI->getIdOperatorePrincipale()->getUsername()){
                if ($schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura' || $schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura_con_rinnovo'){
                    $style = '';
                }else {
                    $style = 'display:none';                          
                }
            }else{
                $style = 'display:none';
            }
        }
        return $style;
    }

    public function valutazioneGenerale(SchedaPAI $schedaPAI, User $user):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() == 'approvata'){
            if($user->getUsername() == $schedaPAI->getIdOperatorePrincipale()->getUsername()){
                $style = ''; 
            }
            elseif(in_array("ROLE_ADMIN", $user->getRoles())){
                $style = '';
            }
            else{
                $style = 'display:none';  
            }
        }
        else{
            $style = 'display:none'; 
        }

        return $style;
    }

    public function valutazioneFiguraProfessionale(SchedaPAI $schedaPAI, User $user):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'verifica' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo'){
            $style = 'display:none';
        }else{
            if ($user->getUsername() == $schedaPAI->getIdOperatorePrincipale()->getUsername()){
                $style = '';
            }
            elseif(in_array("ROLE_ADMIN", $user->getRoles())){
                $style = '';
            }
            else{
                for($i=0; $i<count($schedaPAI->getidOperatoreSecondarioInf()); $i++){
                    if ($schedaPAI->getidOperatoreSecondarioInf()[$i]->getUsername() == $user->getUsername())
                        return '';
                }
                for($i=0; $i<count($schedaPAI->getidOperatoreSecondarioAsa()); $i++){
                    if ($schedaPAI->getidOperatoreSecondarioAsa()[$i]->getUsername() == $user->getUsername())
                        return '';
                }
                for($i=0; $i<count($schedaPAI->getidOperatoreSecondarioTdr()); $i++){
                    if ($schedaPAI->getidOperatoreSecondarioTdr()[$i]->getUsername()== $user->getUsername())
                        return '';
                }
                for($i=0; $i<count($schedaPAI->getidOperatoreSecondarioLog()); $i++){
                    if ($schedaPAI->getidOperatoreSecondarioLog()[$i]->getUsername() == $user->getUsername())
                        return '';
                }
                for($i=0; $i<count($schedaPAI->getidOperatoreSecondarioOss()); $i++){
                    if ($schedaPAI->getidOperatoreSecondarioOss()[$i]->getUsername() == $user->getUsername())
                        return '';
                }
                $style = 'display:none';
            }
        }
        return $style;
    }

    public function parereMmg(SchedaPAI $schedaPAI, User $user):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() == 'nuova' || $schedaPAI->getCurrentPlace() == 'approvata' || $schedaPAI->getCurrentPlace() == 'chiusa' || $schedaPAI->getCurrentPlace() == 'chiusa_con_rinnovo' ){
            $style = 'display:none';
        }
        else{
            if(in_array("ROLE_ADMIN", $user->getRoles())){
                $style = '';
            }
            elseif($user->getUsername() == $schedaPAI->getIdOperatorePrincipale()->getUsername()){
                $style = '';
            }
            else{
                $style = 'display:none';
            }
        }
        return $style;
    }

    public function chiusuraServizio(SchedaPAI $schedaPAI, User $user):string
    {
        $style = null;

        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            if ($schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura_con_rinnovo' || $schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura'){
                $style = '';
            }else{
                $style = 'display:none';
            }
        }else{
            if ($user->getUsername() == $schedaPAI->getIdOperatorePrincipale()->getUsername()){
                if ($schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura_con_rinnovo' || $schedaPAI->getCurrentPlace() == 'in_attesa_di_chiusura'){
                    $style = '';
                }else{
                    $style = 'display:none';
                }
            }
            else{
                $style = 'display:none';
            }
        } 
        return $style;
    }

    public function barthel(SchedaPAI $schedaPAI):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo' && $schedaPAI->getCurrentPlace() != 'verifica'){
            $style = 'display:none';
        } elseif ($schedaPAI->isAbilitaBarthel() == false){
            $style = 'display:none';
        } else{
            $style = '';
        }
        return $style;
    }

    public function braden(SchedaPAI $schedaPAI):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo' && $schedaPAI->getCurrentPlace() != 'verifica'){
            $style = 'display:none';
        } elseif ($schedaPAI->isAbilitaBraden() == false){
            $style = 'display:none';
        } else{
            $style = '';
        }
        return $style;
    }

    public function spmsq(SchedaPAI $schedaPAI):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo' && $schedaPAI->getCurrentPlace() != 'verifica'){
            $style = 'display:none';
        } elseif ($schedaPAI->isAbilitaSpmsq() == false){
            $style = 'display:none';
        } else{
            $style = '';
        }
        return $style;
    }

    public function tinetti(SchedaPAI $schedaPAI):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo' && $schedaPAI->getCurrentPlace() != 'verifica'){
            $style = 'display:none';
        } elseif ($schedaPAI->isAbilitaTinetti() == false){
            $style = 'display:none';
        } else{
            $style = '';
        }
        return $style;
    }

    public function vas(SchedaPAI $schedaPAI):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo' && $schedaPAI->getCurrentPlace() != 'verifica'){
            $style = 'display:none';
        } elseif ($schedaPAI->isAbilitaVas() == false){
            $style = 'display:none';
        } else{
            $style = '';
        }
        return $style;
    }

    public function lesioni(SchedaPAI $schedaPAI):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo' && $schedaPAI->getCurrentPlace() != 'verifica'){
            $style = 'display:none';
        } elseif ($schedaPAI->isAbilitaLesioni() == false){
            $style = 'display:none';
        } else{
            $style = '';
        }
        return $style;
    }

    public function painad(SchedaPAI $schedaPAI):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo' && $schedaPAI->getCurrentPlace() != 'verifica'){
            $style = 'display:none';
        } elseif ($schedaPAI->isAbilitaPainad() == false){
            $style = 'display:none';
        } else{
            $style = '';
        }
        return $style;
    }

    public function cdr(SchedaPAI $schedaPAI):string
    {
        $style = null;

        if ($schedaPAI->getCurrentPlace() != 'attiva' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura' && $schedaPAI->getCurrentPlace() != 'in_attesa_di_chiusura_con_rinnovo' && $schedaPAI->getCurrentPlace() != 'verifica'){
            $style = 'display:none';
        } elseif ($schedaPAI->isAbilitaCdr() == false){
            $style = 'display:none';
        } else{
            $style = '';
        }
        return $style;
    }
}
