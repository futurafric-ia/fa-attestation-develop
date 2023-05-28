<?php

namespace Support\AdvancedFilters;

use Illuminate\Support\Str;

class CustomQueryBuilder
{
    public function apply($query, $data)
    {
        if (isset($data['filter'])) {
            foreach ($data['filter'] as $filter) {
                $filter['match'] = $data['filter_match'] ?? 'and';
                $this->makeFilter($query, $filter);
            }
        }

        return $query;
    }

    protected function makeFilter($query, $filter): void
    {
        // find the type of column
        if (false !== strpos($filter['column'], '.')) {
            // nested column
            [$relation, $filter['column']] = explode('.', $filter['column']);

            $filter['match'] = 'and';

            if ('count' === $filter['column']) {
                $this->{Str::camel($filter['operator'])}($filter, $query, $relation);
            } else {
                $query->whereHas($relation, function ($q) use ($filter) {
                    $this->{Str::camel($filter['operator'])}($filter, $q);
                });
            }
        } else {
            // normal column
            $this->{Str::camel($filter['operator'])}($filter, $query);
        }
    }

    public function equalTo($filter, $query)
    {
        return $query->where($filter['column'], '=', $filter['query_1'], $filter['match']);
    }

    public function notEqualTo($filter, $query)
    {
        return $query->where($filter['column'], '<>', $filter['query_1'], $filter['match']);
    }

    public function lessThan($filter, $query)
    {
        return $query->where($filter['column'], '<', $filter['query_1'], $filter['match']);
    }

    public function greaterThan($filter, $query)
    {
        return $query->where($filter['column'], '>', $filter['query_1'], $filter['match']);
    }

    public function between($filter, $query)
    {
        return $query->whereBetween($filter['column'], [
            $filter['query_1'], $filter['query_2'],
        ], $filter['match']);
    }

    public function notBetween($filter, $query)
    {
        return $query->whereNotBetween($filter['column'], [
            $filter['query_1'], $filter['query_2'],
        ], $filter['match']);
    }

    public function contains($filter, $query)
    {
        return $query->where($filter['column'], 'like', '%' . $filter['query_1'] . '%', $filter['match']);
    }

    public function startsWith($filter, $query)
    {
        return $query->where($filter['column'], 'like', $filter['query_1'] . '%', $filter['match']);
    }

    public function endsWith($filter, $query)
    {
        return $query->where($filter['column'], 'like', '%' . $filter['query_1'], $filter['match']);
    }

    public function inThePast($filter, $query)
    {
        $end = now()->endOfDay();
        $begin = now();

        switch ($filter['query_2']) {
            case 'hours':
                $begin->subHours($filter['query_1']);

                break;
            case 'months':
                $begin->subMonths($filter['query_1'])->startOfDay();

                break;
            case 'years':
                $begin->subYears($filter['query_1'])->startOfDay();

                break;
            default:
                $begin->subDays($filter['query_1'])->startOfDay();

                break;
        }

        return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
    }

    public function inTheNext($filter, $query)
    {
        $begin = now()->startOfDay();
        $end = now();

        switch ($filter['query_2']) {
            case 'hours':
                $end->addHours($filter['query_1']);

                break;
            case 'months':
                $end->addMonths($filter['query_1'])->endOfDay();

                break;
            case 'years':
                $end->addYears($filter['query_1'])->endOfDay();

                break;
            default:
                $end->addDays($filter['query_1'])->endOfDay();

                break;
        }

        return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
    }

    public function inThePeriod($filter, $query)
    {
        $begin = now();
        $end = now();

        switch ($filter['query_1']) {
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
            default:
                break;
        }

        return $query->whereBetween($filter['column'], [$begin, $end], $filter['match']);
    }

    public function equalToCount($filter, $query, $relation)
    {
        return $query->has($relation, '=', $filter['query_1']);
    }

    public function notEqualToCount($filter, $query, $relation)
    {
        return $query->has($relation, '<>', $filter['query_1']);
    }

    public function lessThanCount($filter, $query, $relation)
    {
        return $query->has($relation, '<', $filter['query_1']);
    }

    public function greaterThanCount($filter, $query, $relation)
    {
        return $query->has($relation, '>', $filter['query_1']);
    }
}
