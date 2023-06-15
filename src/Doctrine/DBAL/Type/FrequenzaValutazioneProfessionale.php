<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class FrequenzaValutazioneProfessionale extends EnumType
{

    protected $name = 'Frequenza';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        '1v/settimana' => '1v/settimana',
        '2v/settimana' => '2v/settimana',
        '3v/settimana' => '3v/settimana',
        '4v/settimana' => '4v/settimana',
        '5v/settimana' => '5v/settimana',
        '6v/settimana' => '6v/settimana',
        'gg alterni' => 'gg alterni',
        'quotidiano' => 'quotidiano',
    );
}