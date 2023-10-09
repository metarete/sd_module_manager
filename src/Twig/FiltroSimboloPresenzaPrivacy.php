<?php

namespace App\Twig;

use App\Entity\Paziente;
use App\Service\SetterSimboloPrivacyService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FiltroSimboloPresenzaPrivacy extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('filtroPrivacy', [$this, 'filtroPrivacy']),
            new TwigFilter('filtroColoreSimboloPrivacy', [$this, 'filtroColoreSimboloPrivacy']),
        ];
    }

    public function filtroPrivacy(Paziente $paziente):string
    {
        $setterSimboloPrivacyService = new SetterSimboloPrivacyService();
        $simbolo = $setterSimboloPrivacyService->settaSimboloPrivacy($paziente);
        return $simbolo;
    }

    public function filtroColoreSimboloPrivacy(Paziente $paziente):string
    {
        $setterSimboloPrivacyService = new SetterSimboloPrivacyService();
        $colore = $setterSimboloPrivacyService->settaColorePrivacy($paziente);
        return $colore;
    }

}