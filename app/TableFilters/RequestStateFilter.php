<?php

namespace App\TableFilters;

use Domain\Request\States\Approved;
use Domain\Request\States\Cancelled;
use Domain\Request\States\Delivered;
use Domain\Request\States\Pending;
use Domain\Request\States\Rejected;
use Domain\Request\States\Validated;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class RequestStateFilter
{
    public static $title = "Statut de la demande";
    public static $id = 'status';

    public static function make()
    {
        return Filter::make(self::$title)->select([
            '' => "Tous",
            Pending::$name => 'En attente',
            Validated::$name => 'Validées',
            Approved::$name => 'En cours de traitement',
            Delivered::$name => 'Livrées',
            Cancelled::$name => 'Annulées',
            Rejected::$name => 'Rejétées',
        ]);
    }
}
