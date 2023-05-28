<?php

namespace App\Http\Livewire\Scan;

use Domain\Scan\Models\Scan;
use Livewire\Component;

class ListScanMismatches extends Component
{
    public $scan;
    public $shouldReview = false;

    public function mount(Scan $scan)
    {
        $this->scan = $scan;
        $this->shouldReview = $this->scan->mismatches()->count() > 0;
    }

    public function render()
    {
        return view('livewire.scan.list-scan-mismatches');
    }
}
