<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\HasDateFilters;
use EloquentFilter\ModelFilter;

class AttestationFilter extends ModelFilter
{
    use HasDateFilters;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function attestationType($id)
    {
        return $this->whereHas('attestationType', function ($builder) use ($id) {
            return $builder->where('id', $id)->orWhere('slug', $id);
        });
    }

    public function status($name)
    {
        return $this->whereState('state', $name);
    }
}
