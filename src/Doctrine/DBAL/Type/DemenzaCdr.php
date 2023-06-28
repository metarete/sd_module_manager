<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class DemenzaCdr extends EnumType
{

    protected $name = 'DemenzaCdr';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'CDR 0' => 'CDR 0',
        'CDR 0,5' => 'CDR 0,5',
        'CDR 1' => 'CDR 1',
        'CDR 2' => 'CDR 2',
        'CDR 3' => 'CDR 3',
    );
}