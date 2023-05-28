<?php

namespace Support\AdvancedFilters;

use Illuminate\Validation\ValidationException;

/**
 * Trait Dataviewer.
 *
 * @method static advancedFilter()
 */
trait Dataviewer
{
    /**
     * Recupere les parametres de requete et construit dynamiquement la requete.
     *
     * @param $query
     *
     * @return mixed
     *
     * @throws ValidationException
     */
    public function scopeAdvancedFilter($query, $filters)
    {
        try {
            return $this->process($query, $filters);
        } catch (\Throwable $th) {
            return $query;
        }
    }

    public function process($query, $data)
    {
        $v = validator()->make($data, [
            'filter_match' => 'sometimes|required|in:and,or',
            'filter' => 'sometimes|required|array',
            'filter.*.column' => 'required|in:' . $this->whiteListColumns(),
            'filter.*.operator' => 'required_with:filter.*.column|in:' . $this->allowedOperators(),
            'filter.*.query_1' => 'required',
            'filter.*.query_2' => 'required_if:filter.*.operator,between,not_between',
        ]);

        if ($v->fails()) {
            throw new ValidationException($v);
        }

        return (new CustomQueryBuilder())->apply($query, $data);
    }

    protected function whiteListColumns(): string
    {
        return implode(',', $this->allowedFilters ?? []);
    }

    protected function allowedIncludes(): string
    {
        return implode(',', $this->allowedIncludes ?? []);
    }

    protected function allowedOperators(): string
    {
        return implode(',', [
            'equal_to',
            'not_equal_to',
            'less_than',
            'greater_than',
            'between',
            'not_between',
            'contains',
            'starts_with',
            'ends_with',
            'in_the_past',
            'in_the_next',
            'in_the_period',
            'less_than_count',
            'greater_than_count',
            'equal_to_count',
            'not_equal_to_count',
        ]);
    }
}
