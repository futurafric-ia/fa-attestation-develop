<?php

namespace App\TableFilters;

use Domain\Attestation\States\Attributed;
use Domain\Attestation\States\Available;
use Domain\Attestation\States\Cancelled;
use Domain\Attestation\States\Returned;
use Domain\Attestation\States\Used;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class AttestationStateFilter
{
    public static $title = "Statut de l'attestation";
    public static $id = 'status';

    public static function make()
    {
        return Filter::make(self::$title)->select([
            '' => 'Tous',
            Available::$name => 'Disponible',
            Attributed::$name => 'Attribuées',
            Used::$name => 'Utilisées',
            Cancelled::$name => 'Annulées',
            Returned::$name => 'Retournées',
        ]);
    }
}
