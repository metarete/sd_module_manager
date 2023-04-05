<?php

namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class VotiPainad02 extends EnumType
{
    protected $name = 'VotiPainad02';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        2 => 2,
        1 => 1,
        0 => 0,
    );

}