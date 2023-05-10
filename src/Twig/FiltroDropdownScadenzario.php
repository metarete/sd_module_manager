<?php

namespace App\Twig;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use App\Service\SetterDropdownScadenzarioService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltroDropdownScadenzario extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('filtroConfigura', [$this, 'filtroConfigura']),
            new TwigFilter('filtroApprova', [$this, 'filtroApprova']),
            new TwigFilter('filtroDelete', [$this, 'filtroDelete']),
            new TwigFilter('filtroChiudi', [$this, 'filtroChiudi']),
            new TwigFilter('filtroRinnova', [$this, 'filtroRinnova']),
            new TwigFilter('filtroTornaAlVerifica', [$this, 'filtroTornaAlVerifica']),
            new TwigFilter('filtroValutazioneGenerale', [$this, 'filtroValutazioneGenerale']),
            new TwigFilter('filtroValutazioneFiguraProfessionale', [$this, 'filtroValutazioneFiguraProfessionale']),
            new TwigFilter('filtroParereMmg', [$this, 'filtroParereMmg']),
            new TwigFilter('filtroChiusuraServizio', [$this, 'filtroChiusuraServizio']),
            new TwigFilter('filtroBarthel', [$this, 'filtroBarthel']),
            new TwigFilter('filtroBraden', [$this, 'filtroBraden']),
            new TwigFilter('filtroSpmsq', [$this, 'filtroSpmsq']),
            new TwigFilter('filtroTinetti', [$this, 'filtroTinetti']),
            new TwigFilter('filtroVas', [$this, 'filtroVas']),
            new TwigFilter('filtroLesioni', [$this, 'filtroLesioni']),
            new TwigFilter('filtroPainad', [$this, 'filtroPainad']),
        ];
    }

    public function filtroConfigura(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->configura($schedaPAI, $user);
        return $style;
    }

    public function filtroApprova(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->approva($schedaPAI, $user);
        return $style;
    }

    public function filtroDelete(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->delete($schedaPAI, $user);
        return $style;
    }

    public function filtroChiudi(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->chiudi($schedaPAI, $user);
        return $style;
    }

    public function filtroRinnova(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->rinnova($schedaPAI, $user);
        return $style;
    }

    public function filtroTornaAlVerifica(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->tornaAlVerifica($schedaPAI, $user);
        return $style;
    }

    public function filtroValutazioneGenerale(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->valutazioneGenerale($schedaPAI, $user);
        return $style;
    }

    public function filtroValutazioneFiguraProfessionale(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->valutazioneFiguraProfessionale($schedaPAI, $user);
        return $style;
    }

    public function filtroParereMmg(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->parereMmg($schedaPAI, $user);
        return $style;
    }

    public function filtroChiusuraServizio(SchedaPAI $schedaPAI, User $user):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->chiusuraServizio($schedaPAI, $user);
        return $style;
    }

    public function filtroBarthel(SchedaPAI $schedaPAI):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->barthel($schedaPAI);
        return $style;
    }

    public function filtroBraden(SchedaPAI $schedaPAI):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->braden($schedaPAI);
        return $style;
    }

    public function filtroSpmsq(SchedaPAI $schedaPAI):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->spmsq($schedaPAI);
        return $style;
    }

    public function filtroTinetti(SchedaPAI $schedaPAI):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->tinetti($schedaPAI);
        return $style;
    }

    public function filtroVas(SchedaPAI $schedaPAI):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->vas($schedaPAI);
        return $style;
    }

    public function filtroLesioni(SchedaPAI $schedaPAI):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->lesioni($schedaPAI);
        return $style;
    }

    public function filtroPainad(SchedaPAI $schedaPAI):string
    {
        $setterDropdownScadenzarioService = new SetterDropdownScadenzarioService;
        $style = $setterDropdownScadenzarioService->painad($schedaPAI);
        return $style;
    }
}