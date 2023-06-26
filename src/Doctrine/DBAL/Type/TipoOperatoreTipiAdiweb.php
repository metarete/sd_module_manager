<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class TipoOperatoreTipiAdiweb extends EnumType
{

    protected $name = 'TipoOperatoreTipiAdiweb';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'SPEC-MEDICO-SPECIALISTA' => 'SPEC-MEDICO-SPECIALISTA',
        'SPEC-ALTRO-SPECIALISTA' => 'SPEC-ALTRO-SPECIALISTA',
        'SPEC-PALLIATORE' => 'SPEC-PALLIATORE',
        'SPEC-GERIATRA' => 'SPEC-GERIATRA',
        'SPEC-FISIATRA' => 'SPEC-FISIATRA',
        'SPEC-PSICOLOGO' => 'SPEC-PSICOLOGO',
        'SPEC-LOGOPEDISTA' => 'SPEC-LOGOPEDISTA',
        'SPEC-TERAPISTA-OCCUPAZIONALE' => 'SPEC-TERAPISTA-OCCUPAZIONALE',
        'ASA-OSS/OTA' => 'ASA-OSS/OTA',
        'ASA' => 'ASA',
        'IP' => 'IP',
        'FKT' => 'FKT'
    );
}