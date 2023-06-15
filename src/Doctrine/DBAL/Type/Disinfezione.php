<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class Disinfezione extends EnumType
{

    protected $name = 'Disinfezione';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'Acqua Ossigenata' => 'Acqua Ossigenata',
        'Betadine Soluzione' => 'Betadine Soluzione',
        'Clorexidina' => 'Clorexidina',
        'Amuchina Med' => 'Amuchina Med',
        'Altro' => 'Altro',
    );
}