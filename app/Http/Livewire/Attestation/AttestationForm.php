<?php

namespace App\Http\Livewire\Attestation;

use Domain\Attestation\Rules\AttestationExists;
use Livewire\Component;

class AttestationForm extends Component
{
    public $items = [];
    public $limit = null;
    public $type;

    public function mount(int $type, array $items = [], $limit = 50)
    {
        $this->limit = $limit;
        $this->type = $type;
        $initItems = empty($items) ? [$this->defaultItem] : $items;
        $this->items = old('items', $initItems);
    }

    public function getDefaultItemProperty()
    {
        return [
            'attestation_number' => null,
            'insured_name' => null,
            'police_number' => null,
            'immatriculation' => null,
            'address' => null,
            'make' => null,
            'start_date' => null,
            'end_date' => null,
        ];
    }

    public function updated($field)
    {
        $this->validateOnly($field, [
            'items' => ['required', 'array'],
            'items.*.attestation_number' => ['required', 'integer', 'min:0', new AttestationExists($this->type)],
            'items.*.insured_name' => ['required'],
            'items.*.police_number' => ['required'],
            'items.*.immatriculation' => ['required'],
            'items.*.address' => ['required'],
            'items.*.make' => ['nullable'],
            'items.*.start_date' => ['nullable', 'date', 'before:' . date('Y-m-d')],
            'items.*.end_date' => ['nullable', 'date', 'after_or_equal:items.*.start_date'],
        ]);

        $this->emitUp('itemsUpdated', $this->items);
    }

    public function addItem(): void
    {
        if (! $this->canAddMoreItems()) {
            return;
        }

        $this->items[] = $this->defaultItem;
    }

    public function removeItem(int $i): void
    {
        unset($this->items[$i]);

        $this->items = array_values($this->items);
    }

    public function canAddMoreItems(): bool
    {
        return count($this->items) < $this->limit;
    }

    public function render()
    {
        return view('livewire.attestation.attestation-form');
    }
}
