<?php

namespace Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class Date
{
    /**
     * List of months.
     *
     * @var array
     */
    public const MONTHS = ['Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Aout', 'Sept', 'Oct', 'Nov', 'Déc'];

    /**
     * Determine day name from given integer.
     *
     * @param int $num
     * @return string
     */
    public static function dayFromInt(int $num): string
    {
        return Arr::get(Carbon::getDays(), $num);
    }

    /**
     * Determine month name from given integer.
     *
     * @param int $num
     * @return string
     */
    public static function monthFromInt(int $num): ?string
    {
        --$num;

        if (isset(self::MONTHS[$num])) {
            return null;
        }

        return Arr::get(self::MONTHS, $num);
    }
}
