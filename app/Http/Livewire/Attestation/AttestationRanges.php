<?php

namespace App\Http\Livewire\Attestation;

use Livewire\Component;

class AttestationRanges extends Component
{
    public $ranges = [];
    public $limit = null;
    public $showQuantity = null;

    public function mount(array $ranges = [], $limit = 10, $showQuantity = true)
    {
        $this->limit = $limit;
        $this->showQuantity = $showQuantity;
        $initRanges = empty($ranges) ? [['range_start' => null, 'range_end' => null]] : $ranges;
        $this->ranges = old('ranges', $initRanges);
    }

    public function getQuantityProperty()
    {
        return array_reduce($this->ranges, function ($acc, $current) {
            $range_start = (int) $current['range_start'] ? $current['range_start'] : 0;
            $range_end = (int) $current['range_end'] ? $current['range_end'] : 0;

            if ($range_start > 0 && $range_end > 0) {
                $total = ($range_end - $range_start) + 1;

                return $this->isNegativeNumber($total) ? 0 : $acc + $total;
            }

            return $acc + 0;
        }, 0);
    }

    public function updated($field)
    {
        $this->validateOnly($field, [
            'ranges' => ['required', 'array'],
            'ranges.*.range_start' => ['required', 'integer', 'min:0'],
            'ranges.*.range_end' => ['required', 'integer', 'min:0'],
        ]);

        $this->emit('rangesUpdated', $this->ranges, $this->quantity);
    }

    public function addRange(): void
    {
        if (! $this->canAddMoreRanges()) {
            return;
        }

        $this->ranges[] = ['range_start' => null, 'range_end' => null];
    }

    public function removeRange(int $i): void
    {
        unset($this->ranges[$i]);

        $this->ranges = array_values($this->ranges);
    }

    public function canAddMoreRanges(): bool
    {
        return count($this->ranges) < $this->limit;
    }

    public function render()
    {
        return view('livewire.attestation.attestation-ranges', ['quantity' => $this->quantity]);
    }

    private function isNegativeNumber(int $number): bool
    {
        return strpos((string) $number, '-') === 0;
    }
}
