<?php

namespace App\Service;

use App\Entity\EntityPAI\ValutazioneGenerale;
use Doctrine\ORM\EntityManagerInterface;

class AltraTipologiaAssistenzaService
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAltreTipologieAssistenza(ValutazioneGenerale $valutazioneGenerale = null): array
    {
        $em = $this->entityManager;
        $altreTipologiaAssistenza = [];
        if ($valutazioneGenerale == null)
            return $altreTipologiaAssistenza;
        else {
            if ($valutazioneGenerale->isBuonoSociale() == true)
                array_push($altreTipologiaAssistenza, 'Buono Sociale');
            if ($valutazioneGenerale->isTrasporti() == true)
                array_push($altreTipologiaAssistenza, 'Trasporti');
            if ($valutazioneGenerale->isVoucherSociale() == true)
                array_push($altreTipologiaAssistenza, 'Voucher Sociale');
            if ($valutazioneGenerale->isSad() == true)
                array_push($altreTipologiaAssistenza, 'Sad');
            if ($valutazioneGenerale->isPasti() == true)
                array_push($altreTipologiaAssistenza, 'Pasti');
            if ($valutazioneGenerale->isAssistenzaDomiciliare() == true)
                array_push($altreTipologiaAssistenza, 'Assistenza Domiciliare Socio-Educativa');
            if ($valutazioneGenerale->isContributoCaregiver() == true)
                array_push($altreTipologiaAssistenza, 'Contributo Caregiver');
        }
        $em->flush();
        return $altreTipologiaAssistenza;
    }
}
