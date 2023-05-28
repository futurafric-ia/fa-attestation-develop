<?php

namespace App\ModelFilters\Traits;

trait HasDateFilters
{
    public function fromDate($value)
    {
        return $this->where('created_at', '>=', $value);
    }

    public function toDate($value)
    {
        return $this->where('created_at', '<=', $value);
    }

    public function inThePeriod($value)
    {
        $begin = now();
        $end = now();

        switch ($value) {
            case 'today':
                $begin->startOfDay();
                $end->endOfDay();

                break;
            case 'yesterday':
                $begin->subDay()->startOfDay();
                $end->subDay()->endOfDay();

                break;
            case 'tomorrow':
                $begin->addDay()->startOfDay();
                $end->addDay()->endOfDay();

                break;
            case 'last_month':
                $begin->subMonth()->startOfMonth();
                $end->subMonth()->endOfMonth();

                break;
            case 'this_month':
                $begin->startOfMonth();
                $end->endOfMonth();

                break;
            case 'next_month':
                $begin->addMonth()->startOfMonth();
                $end->addMonth()->endOfMonth();

                break;
            case 'last_year':
                $begin->subYear()->startOfYear();
                $end->subYear()->endOfYear();

                break;
            case 'this_year':
                $begin->startOfYear();
                $end->endOfYear();

                break;
            case 'next_year':
                $begin->addYear()->startOfYear();
                $end->addYear()->endOfYear();

                break;
            case 'this_week':
                $begin->startOfWeek();
                $end->endOfWeek();

                break;
            case 'last_week':
                $begin->subWeek()->startOfWeek();
                $end->subWeek()->endOfWeek();

                break;
            case 'this_quarter':
                $begin->startOfMonth();
                $end->addMonths(2)->endOfMonth();

                break;
            case 'last_quarter':
                $begin->subMonths(3)->startOfMonth();
                $end->subMonth()->endOfMonth();

                break;
            default:
                break;
        }

        return $this->whereBetween('created_at', [$begin, $end]);
    }
}
