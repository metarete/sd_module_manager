<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class TipoOperatore extends EnumType
{

    protected $name = 'TipoOperatore';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'Infermiere' => 'INF',
        'Terapista della riabilitazione' => 'TDR',
        'LOG' => 'LOG',
        'Ausiliario Socio Assistenziale' => 'ASA',
        'Operatore Socio Sanitario' => 'OSS'
    );
}