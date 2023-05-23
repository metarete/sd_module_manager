<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class FANF extends EnumType
{

    protected $name = 'FANF';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'nessuna' => 'nessuna',
        'presenza 24h su 24' => 'presenza 24h su 24',
        'presenza saltuaria a ore nell arco della settimana' => 'presenza saltuaria a ore nell arco della settimana',
        'solo giorni feriali' => 'solo giorni feriali',
    );
}