<?php

namespace App\Service;

use App\Entity\Paziente;

class SetterSimboloPrivacyService
{
    const PRESENTE = 'bi bi-check-circle-fill';
    const NON_PRESENTE = 'bi bi-x-circle-fill';
    const VERDE = 'color:green';
    const ROSSO = 'color:red';

    public function settaSimboloPrivacy(Paziente $paziente): string
    {
        $simbolo = null;

        if ($paziente->getAudioPrivacy() != null) {
            $simbolo = self::PRESENTE;
        } else {
            $simbolo = self::NON_PRESENTE;
        }


        return $simbolo;
    }

    public function settaColorePrivacy(Paziente $paziente): string
    {
        $colore = null;

        if ($paziente->getAudioPrivacy() != null) {
            $colore = self::VERDE;
        } else {
            $colore = self::ROSSO;
        }


        return $colore;
    }
}
