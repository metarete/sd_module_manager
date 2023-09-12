<?php

namespace App\Security;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class VoterPermessiUtente extends Voter
{
    const VISUALIZZA_SCHEDA_COMPLETA = "visualizza_scheda_completa";
    const CONFIGURA = "configura";
    const APPROVA = "approva";
    const ELIMINA = "elimina";
    const CHIUDI = "chiudi";
    const NON_RINNOVARE = "non_rinnovare";
    const RINNOVARE = "rinnovare";
    const VISUALIZZA_DATI_ASSISTITO = "visualizza_dati_assistito";
    const MODIFICA_SCALA_VALUTAZIONE = "modifica_scala_valutazione";
    const ELIMINA_SCALA_VALUTAZIONE = "elimina_scala_valutazione";
    const CREA_VALUTAZIONE_GENERALE = "crea_valutazione_generale";
    const CREA_VALUTAZIONE_FIGURA_PROFESSIONALE = "crea_valutazione_figura_professionale";
    const CREA_PARERE_MMG = "crea_parere_mmg";
    const CREA_CHIUSURA_SERVIZIO = "crea_chiusura_servizio";
    const CREA_BARTHEL = "crea_barthel";
    const CREA_BRADEN = "crea_braden";
    const CREA_SPMSQ = "crea_spmsq";
    const CREA_TINETTI = "crea_tinetti";
    const CREA_VAS = "crea_vas";
    const CREA_LESIONI = "crea_lesioni";
    const CREA_PAINAD = "crea_painad";
    const CREA_CDR = "crea_cdr";

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [
            self::VISUALIZZA_SCHEDA_COMPLETA,
            self::CONFIGURA,
            self::APPROVA,
            self::ELIMINA,
            self::CHIUDI,
            self::NON_RINNOVARE,
            self::RINNOVARE,
            self::VISUALIZZA_DATI_ASSISTITO,
            self::MODIFICA_SCALA_VALUTAZIONE,
            self::ELIMINA_SCALA_VALUTAZIONE,
            self::CREA_VALUTAZIONE_GENERALE,
            self::CREA_VALUTAZIONE_FIGURA_PROFESSIONALE,
            self::CREA_PARERE_MMG,
            self::CREA_CHIUSURA_SERVIZIO,
            self::CREA_BARTHEL,
            self::CREA_BRADEN,
            self::CREA_SPMSQ,
            self::CREA_TINETTI,
            self::CREA_VAS,
            self::CREA_LESIONI,
            self::CREA_PAINAD,
            self::CREA_CDR,
        ])) {

            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof SchedaPAI) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        $schedaPai = $subject;

        return match ($attribute) {
            self::VISUALIZZA_SCHEDA_COMPLETA => $this->canVisualizzaSchedaCompleta($schedaPai, $user),
            self::CONFIGURA => $this->canConfigura($schedaPai, $user),
            self::APPROVA => $this->canApprova($schedaPai, $user),
            self::ELIMINA => $this->canElimina($schedaPai, $user),
            self::CHIUDI => $this->canChiudi($schedaPai, $user),
            self::NON_RINNOVARE => $this->canNonRinnovare($schedaPai, $user),
            self::RINNOVARE => $this->canRinnovare($schedaPai, $user),
            self::VISUALIZZA_DATI_ASSISTITO => $this->canVisualizzaDatiAssistito($schedaPai, $user),
            self::MODIFICA_SCALA_VALUTAZIONE => $this->canModificaScalaValutazione($schedaPai, $user),
            self::ELIMINA_SCALA_VALUTAZIONE => $this->canEliminaScalaValutazione($schedaPai, $user),
            self::CREA_VALUTAZIONE_GENERALE => $this->canCreaValutazioneGenerale($schedaPai, $user),
            self::CREA_VALUTAZIONE_FIGURA_PROFESSIONALE => $this->canCreaValutazioneFiguraProfessionale($schedaPai, $user),
            self::CREA_PARERE_MMG => $this->canCreaParereMmg($schedaPai, $user),
            self::CREA_CHIUSURA_SERVIZIO => $this->canCreaChiusuraServizio($schedaPai, $user),
            self::CREA_BARTHEL => $this->canCreaBarthel($schedaPai, $user),
            self::CREA_BRADEN => $this->canCreaBraden($schedaPai, $user),
            self::CREA_SPMSQ => $this->canCreaSpmsq($schedaPai, $user),
            self::CREA_TINETTI => $this->canCreaTinetti($schedaPai, $user),
            self::CREA_VAS => $this->canCreaVas($schedaPai, $user),
            self::CREA_LESIONI => $this->canCreaLesioni($schedaPai, $user),
            self::CREA_PAINAD => $this->canCreaPainad($schedaPai, $user),
            self::CREA_CDR => $this->canCreaCdr($schedaPai, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function checkRuoloPrincipaleSecondario(SchedaPAI $schedaPAI, User $user): bool
    {
        // se l'utente loggato è admin o è user ed è assegnato alla scheda pai
        $roles = $user->getRoles();

        if (in_array("ROLE_ADMIN", $roles)) {
            return true;
        } else {
            return $this->checkOperatoriPrincipaliSecondari($schedaPAI, $user);
        }
    }

    private function checkOperatoriPrincipaliSecondari(SchedaPAI $schedaPAI, User $user): bool
    {
        //l'utente è operatore principale
        if ($user == $schedaPAI->getIdOperatorePrincipale()) {
            return true;
        }
        //verifico se l'utente è operatore secondario
        else {
            //appena trovo l'utente in una categoria di operatori secondari esco con true
            $operatoriSecondari = $schedaPAI->getidOperatoreSecondarioInf();
            for ($i = 0; $i < count($operatoriSecondari); $i++) {
                if ($user == $operatoriSecondari[$i]) {
                    return true;
                }
            }
            $operatoriSecondari = $schedaPAI->getidOperatoreSecondarioAsa();
            for ($i = 0; $i < count($operatoriSecondari); $i++) {
                if ($user == $operatoriSecondari[$i]) {
                    return true;
                }
            }
            $operatoriSecondari = $schedaPAI->getidOperatoreSecondarioLog();
            for ($i = 0; $i < count($operatoriSecondari); $i++) {
                if ($user == $operatoriSecondari[$i]) {
                    return true;
                }
            }
            $operatoriSecondari = $schedaPAI->getidOperatoreSecondarioTdr();
            for ($i = 0; $i < count($operatoriSecondari); $i++) {
                if ($user == $operatoriSecondari[$i]) {
                    return true;
                }
            }
            $operatoriSecondari = $schedaPAI->getidOperatoreSecondarioOss();
            for ($i = 0; $i < count($operatoriSecondari); $i++) {
                if ($user == $operatoriSecondari[$i]) {
                    return true;
                }
            }
            //non sono operatore secondario quindi non posso accedere
            return false;
        }
    }

    private function checkRuoloPrincipale(SchedaPAI $schedaPAI, User $user): bool
    {
        // se l'utente loggato è admin o è user ed è assegnato alla scheda pai
        $roles = $user->getRoles();

        if (in_array("ROLE_ADMIN", $roles)) {
            return true;
        } else {

            //l'utente è operatore principale
            if ($user == $schedaPAI->getIdOperatorePrincipale()) {
                return true;
            } else {

                return false;
            }
        }
    }

    private function canVisualizzaSchedaCompleta(SchedaPAI $schedaPAI, User $user): bool
    {
        return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
    }

    private function canConfigura(SchedaPAI $schedaPAI, User $user): bool
    {
        return $this->checkRuoloPrincipale($schedaPAI, $user);
    }
    private function canApprova(SchedaPAI $schedaPAI, User $user): bool
    {
        // se l'utente loggato è admin o è user ed è assegnato alla scheda pai
        return $this->checkRuoloPrincipale($schedaPAI, $user);
    }
    private function canElimina(SchedaPAI $schedaPAI, User $user): bool
    {
        return $this->checkRuoloPrincipale($schedaPAI, $user);
    }

    private function canChiudi(SchedaPAI $schedaPAI, User $user): bool
    {
        return $this->checkRuoloPrincipale($schedaPAI, $user);
    }

    private function canNonRinnovare(SchedaPAI $schedaPAI, User $user): bool
    {
        return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
    }

    private function canRinnovare(SchedaPAI $schedaPAI, User $user): bool
    {
        return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
    }

    private function canVisualizzaDatiAssistito(SchedaPAI $schedaPAI, User $user): bool
    {
        return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
    }

    private function canModificaScalaValutazione(SchedaPAI $schedaPAI, User $user): bool
    {
        if ($schedaPAI->getCurrentPlace() != "chiusa" && $schedaPAI->getCurrentPlace() != "chiusa_con_rinnovo"){
            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        }
        else{
            return false;
        }
    }

    private function canEliminaScalaValutazione(SchedaPAI $schedaPAI, User $user): bool
    {
        if ($schedaPAI->getCurrentPlace() != "chiusa" && $schedaPAI->getCurrentPlace() != "chiusa_con_rinnovo"){
            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        }
        else{
            return false;
        }
    }

    private function canCreaValutazioneGenerale(SchedaPAI $schedaPAI, User $user): bool
    {
        if ($schedaPAI->getCurrentPlace() == "approvata" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica" ){
            return $this->checkRuoloPrincipale($schedaPAI, $user);
        }
        else{
            return false;
        }
    }

    private function canCreaValutazioneFiguraProfessionale(SchedaPAI $schedaPAI, User $user): bool
    {
        if ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica"){
            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        }
        else{
            return false;
        }
    }

    private function canCreaParereMmg(SchedaPAI $schedaPAI, User $user): bool
    {
        if ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica"){
            return $this->checkRuoloPrincipale($schedaPAI, $user);
        }
        else{
            return false;
        }
    }

    private function canCreaChiusuraServizio(SchedaPAI $schedaPAI, User $user): bool
    {
        if ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica"){
            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        }
        else{
            return false;
        }
            
    }

    private function canCreaBarthel(SchedaPAI $schedaPAI, User $user): bool
    {

        //verifico che sia abilitata la possibilità di creare la scheda

        if ($schedaPAI->isAbilitaBarthel() && ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica")) {

            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        } else {
            return false;
        }
    }

    private function canCreaBraden(SchedaPAI $schedaPAI, User $user): bool
    {

        //verifico che sia abilitata la possibilità di creare la scheda

        if ($schedaPAI->isAbilitaBraden() && ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica")) {

            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        } else {
            return false;
        }
    }

    private function canCreaSpmsq(SchedaPAI $schedaPAI, User $user): bool
    {

        //verifico che sia abilitata la possibilità di creare la scheda

        if ($schedaPAI->isAbilitaSpmsq() && ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica")) {

            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        } else {
            return false;
        }
    }

    private function canCreaTinetti(SchedaPAI $schedaPAI, User $user): bool
    {

        //verifico che sia abilitata la possibilità di creare la scheda

        if ($schedaPAI->isAbilitaTinetti() && ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica")) {

            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        } else {
            return false;
        }
    }

    private function canCreaVas(SchedaPAI $schedaPAI, User $user): bool
    {

        //verifico che sia abilitata la possibilità di creare la scheda

        if ($schedaPAI->isAbilitaVas() && ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica")) {

            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        } else {
            return false;
        }
    }

    private function canCreaLesioni(SchedaPAI $schedaPAI, User $user): bool
    {

        //verifico che sia abilitata la possibilità di creare la scheda

        if ($schedaPAI->isAbilitaLesioni() && ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica")) {

            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        } else {
            return false;
        }
    }

    private function canCreaPainad(SchedaPAI $schedaPAI, User $user): bool
    {

        //verifico che sia abilitata la possibilità di creare la scheda

        if ($schedaPAI->isAbilitaPainad() && ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica")) {

            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        } else {
            return false;
        }
    }

    private function canCreaCdr(SchedaPAI $schedaPAI, User $user): bool
    {

        //verifico che sia abilitata la possibilità di creare la scheda

        if ($schedaPAI->isAbilitaCdr() && ($schedaPAI->getCurrentPlace() == "attiva" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura" || $schedaPAI->getCurrentPlace() == "in_attesa_di_chiusura_con_rinnovo" || $schedaPAI->getCurrentPlace() == "verifica")) {

            return $this->checkRuoloPrincipaleSecondario($schedaPAI, $user);
        } else {
            return false;
        }
    }
}
