<?php

use Carbon\Carbon;
use Domain\Authorization\Models\Role;
use Domain\Broker\Models\Broker;
use Illuminate\Support\Facades\DB;
use Support\Date;

if (! function_exists('homeRoute')) {
    function homeRoute(): string
    {
        $redirectTo = 'notFound';

        if (auth()->check() && auth()->user()->roles()->exists()) {
            $userRole = auth()->user()->roles()->first()->id;

            $redirectTo = [
                Role::SUPER_ADMIN => 'backend.dashboard',
                Role::ADMIN => 'dashboard.admin',
                Role::BROKER => 'dashboard.broker',
                Role::VALIDATOR => 'dashboard.validator',
                Role::SUPERVISOR => 'dashboard.supervisor',
                Role::MANAGER => 'dashboard.manager',
                Role::AUDITOR => 'dashboard.auditor',
                Role::SOUSCRIPTOR => 'dashboard.souscriptor',
            ][$userRole] ?? $redirectTo;
        }

        return $redirectTo;
    }
}

if (! function_exists('setAllLocale')) {
    /**
     * @param $locale
     */
    function setAllLocale(string $locale)
    {
        setAppLocale($locale);
        setPHPLocale($locale);
        setCarbonLocale($locale);
    }
}

if (! function_exists('setAppLocale')) {
    /**
     * @param $locale
     */
    function setAppLocale($locale)
    {
        app()->setLocale($locale);
    }
}

if (! function_exists('setPHPLocale')) {
    /**
     * @param $locale
     */
    function setPHPLocale($locale)
    {
        setlocale(LC_TIME, $locale);
    }
}

if (! function_exists('setCarbonLocale')) {
    /**
     * @param $locale
     */
    function setCarbonLocale($locale)
    {
        Carbon::setLocale($locale);
    }
}

if (! function_exists('getLocaleName')) {
    /**
     * @param $locale
     *
     * @return mixed
     */
    function getLocaleName($locale)
    {
        return config('saham.locale.languages')[$locale]['name'];
    }
}

if (! function_exists('activeClass')) {
    /**
     * Get the active class if the condition is not falsy.
     *
     * @param        $condition
     * @param string $activeClass
     * @param string $inactiveClass
     *
     * @return string
     */
    function activeClass($condition, $activeClass = 'text-white bg-primary-900', $inactiveClass = ''): string
    {
        return $condition ? $activeClass : $inactiveClass;
    }
}

if (! function_exists('htmlLang')) {
    /**
     * Access the htmlLang helper.
     */
    function htmlLang()
    {
        return str_replace('_', '-', app()->getLocale());
    }
}

if (! function_exists('generateDateId')) {
    /**
     * Generate a unique date based identifier.
     *
     * @param string $table
     * @param string $column
     * @param ?string $prefix
     * @param string $dateFormat
     * @param string|null $date
     * @return string
     */
    function generateDateId(string $table, string $column = "code", ?string $prefix = null, string $dateFormat = "dmY", ?string $date = null): string
    {
        $current_date = $date ?: date($dateFormat);
        $count = DB::table($table)->where($column, 'like', "%{$current_date}%")->count();
        $code = "{$current_date}-" . (++$count);

        return $prefix ? "{$prefix}-{$code}" : $code;
    }
}

if (! function_exists('broker')) {
    /**
     * Returns the broker of the authenticated user.
     *
     * @return null|Broker
     */
    function broker(): ?Broker
    {
        if (auth()->check() && auth()->user()->isBroker()) {
            return auth()->user()->currentBroker;
        }

        return null;
    }
}

if (! function_exists('isDirectoryEmpty')) {
    /**
     * @param string $dir
     * @return bool
     */
    function isDirectoryEmpty(string $dir): bool
    {
        if (! File::isDirectory($dir)) {
            throw new InvalidArgumentException("Le chemin d'accès n'est pas un dossier valide.");
        }

        return ! (new \FilesystemIterator($dir))->valid();
    }
}

if (! function_exists('dayFromInt')) {
    /**
     * Determine day name from the given integer.
     *
     * @param int $num
     * @return string
     */
    function dayFromInt(int $num): string
    {
        return Date::dayFromInt($num);
    }
}

if (! function_exists('monthFromInt')) {
    /**
     * Determine month name from the given integer.
     *
     * @param int $num
     * @return string
     */
    function monthFromInt(int $num): string
    {
        return Date::monthFromInt($num);
    }
}
