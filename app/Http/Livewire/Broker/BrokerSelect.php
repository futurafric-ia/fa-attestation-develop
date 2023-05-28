<?php

namespace App\Http\Livewire\Broker;

use App\Http\Livewire\LivewireSelect;
use Domain\Broker\Models\Broker;
use Illuminate\Support\Collection;

class BrokerSelect extends LivewireSelect
{
    public function options($searchTerm = null): Collection
    {
        return Broker::filter(['name' => $searchTerm ?? ''])
            ->get()
            ->map(fn ($item) => ['value' => $item->id, 'description' => $item->name]);
    }

    public function selectedOption($value)
    {
        return [
            'value' => $value,
            'description' => Broker::find($value)->name
        ];
    }
}
