<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class GradoLesione extends EnumType
{

    protected $name = 'GradoLesione';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'I°' => 'I°',
        'II°/flittene' => 'II°/flittene',
        'III°' => 'III°',
        'IV°/Escara' => 'IV°/Escara',
    );
}