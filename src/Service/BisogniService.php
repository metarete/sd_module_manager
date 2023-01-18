<?php

namespace App\Service;

use App\Entity\EntityPAI\ValutazioneGenerale;
use Doctrine\ORM\EntityManagerInterface;

class BisogniService
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getBisogni(ValutazioneGenerale $valutazioneGenerale = null): array
    {
        $bisogni = [];
        if ($valutazioneGenerale == null)
            return $bisogni;
        else {
            if ($valutazioneGenerale->isBroncoaspirazione() == true)
                array_push($bisogni, 'Broncoaspirazione/Drenaggio posturale');
            if ($valutazioneGenerale->isOssigenoTerapia() == true)
                array_push($bisogni, 'Ossigeno Terapia');
            if ($valutazioneGenerale->isVentiloTerapia() == true)
                array_push($bisogni, 'Ventilo terapia');
            if ($valutazioneGenerale->isTracheotomia() == true)
                array_push($bisogni, 'Tracheotomia');
            if ($valutazioneGenerale->isAlimentazioneAssistita() == true)
                array_push($bisogni, 'Alimentazione assistita');
            if ($valutazioneGenerale->isAlimentazioneEnterale() == true)
                array_push($bisogni, 'Alimentazione enterale');
            if ($valutazioneGenerale->isAlimentazioneParenterale() == true)
                array_push($bisogni, 'Alimentazione parenterale');
            if ($valutazioneGenerale->isGestioneStomia() == true)
                array_push($bisogni, 'Gestione della stomia');
            if ($valutazioneGenerale->isEliminazioneUrina() == true)
                array_push($bisogni, 'Eliminazione urinaria/intestinale');
            if ($valutazioneGenerale->isAlterazioneSonno() == true)
                array_push($bisogni, 'Alterazione del ritmo sonno/sveglia');
            if ($valutazioneGenerale->isEducazioneTerapeutica() == true)
                array_push($bisogni, 'Educazione terapeutica (addestramento/nursing)');
            if ($valutazioneGenerale->isUlcerePrimoSecondoGrado() == true)
                array_push($bisogni, 'Ulcere da decubito di 1 e 2 grado');
            if ($valutazioneGenerale->isUlcereTerzoQuartoGrado() == true)
                array_push($bisogni, 'Ulcere da decubito di 3 e 4 grado');
            if ($valutazioneGenerale->isUlcereCutaneePrimoSecondoGrado() == true)
                array_push($bisogni, 'Ulcere curanee (vascolari, traumatiche, ustioni, postchirurgiche, ecc) di 1 e 2 grado');
            if ($valutazioneGenerale->isUlcereCutaneeTerzoQuartoGrado() == true)
                array_push($bisogni, 'Ulcere curanee (vascolari, traumatiche, ustioni, postchirurgiche, ecc) di 3 e 4 grado');
            if ($valutazioneGenerale->isPrelieviVenosiNonOccasionali() == true)
                array_push($bisogni, 'Prelievi venosi non occasionali');
            if ($valutazioneGenerale->isPrelieviVenosiOccasionali() == true)
                array_push($bisogni, 'Prelievi venosi occasionali');
            if ($valutazioneGenerale->isEcg() == true)
                array_push($bisogni, 'ECG');
            if ($valutazioneGenerale->isTelemetria() == true)
                array_push($bisogni, 'Telemetria');
            if ($valutazioneGenerale->isProceduraTerapeutica() == true)
                array_push($bisogni, 'Procedura Terapeutica (accesso venoso sottocute/intramuscolo)');
            if ($valutazioneGenerale->isGestioneCatetere() == true)
                array_push($bisogni, 'Gestione catetere centrale');
            if ($valutazioneGenerale->isTrasfusioni() == true)
                array_push($bisogni, 'Trasfusioni');
            if ($valutazioneGenerale->isControlloDolore() == true)
                array_push($bisogni, 'Controllo del dolore');
            if ($valutazioneGenerale->isAssistenzaOncologica() == true)
                array_push($bisogni, 'Assistenza stato di terminalità oncologica');
            if ($valutazioneGenerale->isAssistenzaNonOncologica() == true)
                array_push($bisogni, 'Assistenza stato di terminalità non oncologica');
            if ($valutazioneGenerale->isTrattamentoNeurologico() == true)
                array_push($bisogni, 'Trattamento riabilitativo neurologico in presenza di disabilità');
            if ($valutazioneGenerale->isTrattamentoOrtopedico() == true)
                array_push($bisogni, 'Trattamento riabilitativo ortopedico in presenza di disabilità');
            if ($valutazioneGenerale->isTrattamentoMantenimento() == true)
                array_push($bisogni, 'Trattamento riabilitativo di mantenimento in presenza di disabilità');
            if ($valutazioneGenerale->isSupervisioneContinua() == true)
                array_push($bisogni, 'Supervisione continua di utenti con disabilità');
            if ($valutazioneGenerale->isAssistenzaIadl() == true)
                array_push($bisogni, 'Assistenza nelle IADL');
            if ($valutazioneGenerale->isAssistenzaAdl() == true)
                array_push($bisogni, 'Assistenza nelle ADL');
            if ($valutazioneGenerale->isSupportoCaregiver() == true)
                array_push($bisogni, 'Supporto al care giver');
        }
        $this->entityManager->flush();
        return $bisogni;
    }
}
