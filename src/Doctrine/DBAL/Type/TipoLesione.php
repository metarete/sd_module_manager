<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class TipoLesione extends EnumType
{

    protected $name = 'TipoLesione';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'Da decubito' => 'Da decubito',
        'Vascolare' => 'Vascolare',
        'Traumatica' => 'Traumatica',
        'Ustione' => 'Ustione',
        'Chirurgica' => 'Chirurgica',
        'Deiscenza'  => 'Deiscenza'
    );
}