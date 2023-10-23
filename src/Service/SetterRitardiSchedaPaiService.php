<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;

class SetterRitardiSchedaPaiService
{
    const GREY = 'text-grey';
    const SUCCES = 'text-success';
    const DANGER = 'text-danger';
    const WARNING = 'text-warning';

    public function settaColoriBarthel(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->isAbilitaBarthel() == false){
            $colore = self::GREY;
        }
        elseif($schedaPAI->getCurrentPlace()=="chiusura_forzata"){
            $colore = self::WARNING;
        }
        elseif($schedaPAI->getBarthelNumberToday()< $schedaPAI->getCorrectBarthelNumberToday()){
            $colore = self::DANGER;
        }
        else{
            $colore = self::SUCCES;
        }

        return $colore;
    }

    public function settaColoriBraden(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->isAbilitaBraden() == false){
            $colore = self::GREY;
        }
        elseif($schedaPAI->getCurrentPlace()=="chiusura_forzata"){
            $colore = self::WARNING;
        }
        elseif($schedaPAI->getBradenNumberToday()< $schedaPAI->getCorrectBradenNumberToday()){
            $colore = self::DANGER;
        }
        else{
            $colore = self::SUCCES;
        }

        return $colore;
    }

    public function settaColoriSpmsq(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->isAbilitaSpmsq() == false){
            $colore = self::GREY;
        }
        elseif($schedaPAI->getCurrentPlace()=="chiusura_forzata"){
            $colore = self::WARNING;
        }
        elseif($schedaPAI->getSpmsqNumberToday()< $schedaPAI->getCorrectSpmsqNumberToday()){
            $colore = self::DANGER;
        }
        else{
            $colore = self::SUCCES;
        }

        return $colore;
    }

    public function settaColoriTinetti(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->isAbilitaTinetti() == false){
            $colore = self::GREY;
        }
        elseif($schedaPAI->getCurrentPlace()=="chiusura_forzata"){
            $colore = self::WARNING;
        }
        elseif($schedaPAI->getTinettiNumberToday()< $schedaPAI->getCorrectTinettiNumberToday()){
            $colore = self::DANGER;
        }
        else{
            $colore = self::SUCCES;
        }

        return $colore;
    }

    public function settaColoriVas(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->isAbilitaVas() == false){
            $colore = self::GREY;
        }
        elseif($schedaPAI->getCurrentPlace()=="chiusura_forzata"){
            $colore = self::WARNING;
        }
        elseif($schedaPAI->getVasNumberToday()< $schedaPAI->getCorrectVasNumberToday()){
            $colore = self::DANGER;
        }
        else{
            $colore = self::SUCCES;
        }

        return $colore;
    }

    public function settaColoriLesioni(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->isAbilitaLesioni() == false){
            $colore = self::GREY;
        }
        elseif($schedaPAI->getCurrentPlace()=="chiusura_forzata"){
            $colore = self::WARNING;
        }
        elseif($schedaPAI->getLesioniNumberToday()< $schedaPAI->getCorrectLesioniNumberToday()){
            $colore = self::DANGER;
        }
        else{
            $colore = self::SUCCES;
        }

        return $colore;
    }

    public function settaColoriPainad(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->isAbilitaPainad() == false){
            $colore = self::GREY;
        }
        elseif($schedaPAI->getCurrentPlace()=="chiusura_forzata"){
            $colore = self::WARNING;
        }
        elseif($schedaPAI->getPainadNumberToday()< $schedaPAI->getCorrectPainadNumberToday()){
            $colore = self::DANGER;
        }
        else{
            $colore = self::SUCCES;
        }

        return $colore;
    }

    public function settaColoriCdr(SchedaPAI $schedaPAI):string
    {
        $colore = null;

        if($schedaPAI->isAbilitaCdr() == false){
            $colore = self::GREY;
        }
        elseif($schedaPAI->getCurrentPlace()=="chiusura_forzata"){
            $colore = self::WARNING;
        }
        elseif($schedaPAI->getCdrNumberToday()< $schedaPAI->getCorrectCdrNumberToday()){
            $colore = self::DANGER;
        }
        else{
            $colore = self::SUCCES;
        }

        return $colore;
    }
}