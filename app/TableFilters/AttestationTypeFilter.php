<?php

namespace App\TableFilters;

use Rappasoft\LaravelLivewireTables\Views\Filter;

class AttestationTypeFilter
{
    public static $id = 'attestation_type';
    public static $title = "Type d'attestations";

    public static function make()
    {
        return Filter::make(self::$title)->select([
            '' => "Tous",
            'brune' => 'Brune',
            'verte' => 'Verte',
            'jaune' => 'Jaune',
        ]);
    }
}
