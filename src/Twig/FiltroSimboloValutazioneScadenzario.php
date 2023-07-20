<?php

namespace App\Twig;

use App\Entity\EntityPAI\SchedaPAI;
use App\Service\SetterSimboloValutazioneService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltroSimboloValutazioneScadenzario extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('filtroValutazione', [$this, 'filtroValutazione']),
            new TwigFilter('filtroColoreSimboloValutazione', [$this, 'filtroColoreSimboloValutazione']),
        ];
    }

    public function filtroValutazione( SchedaPAI $schedaPAI):string
    {
        $setterSimboloValutazioneService = new SetterSimboloValutazioneService();
        $simbolo = $setterSimboloValutazioneService->settaSimboloValutazione($schedaPAI);
        return $simbolo;
    }

    public function filtroColoreSimboloValutazione( SchedaPAI $schedaPAI):string
    {
        $setterSimboloValutazioneService = new SetterSimboloValutazioneService();
        $colore = $setterSimboloValutazioneService->settaColoreValutazione($schedaPAI);
        return $colore;
    }

}