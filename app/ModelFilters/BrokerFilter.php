<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class BrokerFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function setup()
    {
        if ($this->input('status') === 'with_trashed') {
            return $this->withTrashed();
        }

        if ($this->input('status') === 'trashed') {
            return $this->onlyTrashed();
        }
    }

    public function name($value)
    {
        return $this->where('name', 'like', '%' . $value . '%');
    }

    public function department($id)
    {
        return $this->whereHas('department', function ($builder) use ($id) {
            return $builder->where('id', $id);
        });
    }

    public function createdAt($date)
    {
        return $this->whereCreatedAt($date);
    }
}
