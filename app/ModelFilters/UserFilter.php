<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
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

    public function role($id)
    {
        return $this->whereHas('roles', function ($query) use ($id) {
            return $query->where('roles.id', $id);
        });
    }

    public function department($id)
    {
        return $this->whereHas('departments', function ($query) use ($id) {
            return $query->where('departments.id', $id);
        });
    }

    public function createdAt($date)
    {
        return $this->whereCreatedAt($date);
    }
}
