<?php

namespace App\Twig;

use App\Entity\EntityPAI\SchedaPAI;
use App\Service\SetterRitardiSchedaPaiService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltroColoriScadenzario extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('filtroColoriBarthel', [$this, 'filtroColoriBarthel']),
            new TwigFilter('filtroColoriBraden', [$this, 'filtroColoriBraden']),
            new TwigFilter('filtroColoriSpmsq', [$this, 'filtroColoriSpmsq']),
            new TwigFilter('filtroColoriTinetti', [$this, 'filtroColoriTinetti']),
            new TwigFilter('filtroColoriVas', [$this, 'filtroColoriVas']),
            new TwigFilter('filtroColoriLesioni', [$this, 'filtroColoriLesioni']),
            new TwigFilter('filtroColoriPainad', [$this, 'filtroColoriPainad']),
            new TwigFilter('filtroColoriCdr', [$this, 'filtroColoriCdr']),
        ];
    }

    public function filtroColoriBarthel( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterRitardiSchedaPaiService();
        $textColor = $setterRitardiSchedaPaiService->settaColoriBarthel($schedaPAI);
        return $textColor;
    }

    public function filtroColoriBraden( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterRitardiSchedaPaiService();
        $textColor = $setterRitardiSchedaPaiService->settaColoriBraden($schedaPAI);
        return $textColor;
    }

    public function filtroColoriSpmsq( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterRitardiSchedaPaiService();
        $textColor = $setterRitardiSchedaPaiService->settaColoriSpmsq($schedaPAI);
        return $textColor;
    }

    public function filtroColoriTinetti( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterRitardiSchedaPaiService();
        $textColor = $setterRitardiSchedaPaiService->settaColoriTinetti($schedaPAI);
        return $textColor;
    }

    public function filtroColoriVas( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterRitardiSchedaPaiService();
        $textColor = $setterRitardiSchedaPaiService->settaColoriVas($schedaPAI);
        return $textColor;
    }

    public function filtroColoriLesioni( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterRitardiSchedaPaiService();
        $textColor = $setterRitardiSchedaPaiService->settaColoriLesioni($schedaPAI);
        return $textColor;
    }

    public function filtroColoriPainad( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterRitardiSchedaPaiService();
        $textColor = $setterRitardiSchedaPaiService->settaColoriPainad($schedaPAI);
        return $textColor;
    }

    public function filtroColoriCdr( SchedaPAI $schedaPAI):string
    {
        $setterRitardiSchedaPaiService = new SetterRitardiSchedaPaiService();
        $textColor = $setterRitardiSchedaPaiService->settaColoriCdr($schedaPAI);
        return $textColor;
    }
}