<?php

namespace App\ModelFilters;

use App\ModelFilters\Traits\HasDateFilters;
use Domain\Authorization\Models\Role;
use Domain\Request\States\Approved;
use Domain\Request\States\Delivered;
use Domain\Request\States\Validated;
use Domain\User\Models\User;
use EloquentFilter\ModelFilter;

class RequestFilter extends ModelFilter
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
        /** @var User $user */
        $user = auth()->user();

        if ($name === Approved::$name && $user->hasRole(Role::VALIDATOR)) {
            return $this->whereState('state', [Approved::$name, Validated::$name, Delivered::$name]);
        }

        if ($name === Validated::$name && $user->hasRole(Role::SUPERVISOR)) {
            return $this->whereState('state', [Validated::$name, Delivered::$name]);
        }

        return $this->whereState('state', (array) $name);
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
            return $builder->where('code', $code);
        });
    }
}
