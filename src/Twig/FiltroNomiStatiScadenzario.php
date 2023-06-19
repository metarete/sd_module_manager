<?php

namespace App\Twig;

use App\Entity\EntityPAI\SchedaPAI;
use App\Service\SetterNomiStatoSchedaPaiService;
use Twig\TwigFilter;

class FiltroNomiStatiScadenzario
{
    public function getFilters()
    {
        return [
            new TwigFilter('filtroNomi', [$this, 'filtroNomi']),
            new TwigFilter('filtroBadge', [$this, 'filtroBadge']),
        ];
    }

    public function filtroNomi( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterNomiStatoSchedaPaiService();
        $nomeStato = $setterRitardiSchedaPaiService->settaNomeStato($schedaPAI);
        return $nomeStato;
    }

    public function filtroBadge( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterNomiStatoSchedaPaiService();
        $coloreBadge = $setterRitardiSchedaPaiService->settaColoreBadge($schedaPAI);
        return $coloreBadge;
    }

    public function filtroStatoSDManager( SchedaPAI $schedaPAI): string
    {
        $setterRitardiSchedaPaiService = new SetterNomiStatoSchedaPaiService();
        $coloreStato = $setterRitardiSchedaPaiService->settaColoriStatoSDManager($schedaPAI);
        return $coloreStato;
    }

}