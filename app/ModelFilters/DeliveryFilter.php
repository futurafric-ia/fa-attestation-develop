<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\HasDateFilters;
use EloquentFilter\ModelFilter;

class DeliveryFilter extends ModelFilter
{
    use HasDateFilters;

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function status($name)
    {
        return $this->whereState('state', $name);
    }

    public function attestationType($slug)
    {
        return $this->whereHas('attestationType', function ($builder) use ($slug) {
            return $builder->where('slug', $slug);
        });
    }

    public function broker($code)
    {
        return $this->whereHas('broker', function ($builder) use ($code) {
            return $builder->where('code', $code)->orWhere('id', $code);
        });
    }
}
