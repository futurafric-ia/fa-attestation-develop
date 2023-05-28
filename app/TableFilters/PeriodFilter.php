<?php

namespace App\TableFilters;

use Rappasoft\LaravelLivewireTables\Views\Filter;

class PeriodFilter
{
    public static $title = "Période";
    public static $id = 'in_the_period';

    public static function make($title = '')
    {
        self::$title = $title ?: self::$title;

        return Filter::make(self::$title)->select([
            '' => 'Peu importe',
            'today' => "Aujourd'hui",
            'yesterday' => 'Hier',
            'tomorrow' => 'Demain',
            'last_month' => 'Le mois dernier',
            'this_month' => 'Ce mois',
            'next_month' => 'Le mois prochain',
            'last_year' => "L'année dernière",
            'this_year' => 'Cette année',
            'next_year' => "L'année prochaine"
        ]);
    }
}
