<?php
namespace App\Doctrine\DBAL\Type;

use App\Doctrine\DBAL\Type\EnumType;

class MotivazioneChiusuraForzata extends EnumType
{

    protected $name = 'MotivazioneChiusuraForzata';

    // etichetta => valore
    // mantenere allineate con workflow
    protected $values = array(
        'Completamento del programma assistenziale' => 'Completamento del programma assistenziale',
        'Decesso a domicilio' => 'Decesso a domicilio',
        'Decesso in Hospice' => 'Decesso in Hospice',
        'Decesso in ospedale' => 'Decesso in ospedale',
        'Ricovero in ospedale' => 'Ricovero in ospedale',
        'Trasferimento in altro tipo di cure domiciliari' => 'Trasferimento in altro tipo di cure domiciliari',
        'Trasferimento in Hospice' => 'Trasferimento in Hospice',
        'Trasferimento in residenza sanitaria' => 'Trasferimento in residenza sanitaria',
        'Volontà dell utente o familiare' => 'Volontà dell utente o familiare',
        'Non necessita di ulteriori valutazioni' =>  'Non necessita di ulteriori valutazioni',
        'Altro' => 'Altro',
    );
}