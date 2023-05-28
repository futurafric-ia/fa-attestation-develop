<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\HasDateFilters;
use EloquentFilter\ModelFilter;

class SupplyFilter extends ModelFilter
{
    use HasDateFilters;
    
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function attestationType($slug)
    {
        return $this->whereHas('attestationType', function ($builder) use ($slug) {
            return $builder->where('slug', $slug);
        });
    }
}
