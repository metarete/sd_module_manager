<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class TipoLesione extends EnumType
{

    protected $name = 'TipoLesione';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'Principale' => 'Principale',
        
    );
}