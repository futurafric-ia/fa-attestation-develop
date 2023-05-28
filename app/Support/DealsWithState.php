<?php

namespace Support;

use Arr;

trait DealsWithState
{
    public function hasState($states, string $column = 'state'): bool
    {
        $field = Arr::last(explode('.', $column));

        if (! isset($this->{$field}::$name)) {
            return false;
        }

        return in_array((string)$this->{$field}::$name, (array)$states, true);
    }
}
