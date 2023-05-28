<?php

namespace App\Http\Livewire\Scan;

use Domain\Scan\Models\Scan;
use Livewire\Component;

class ShowScan extends Component
{
    public $scan;

    public function mount(Scan $scan)
    {
        $this->scan = $scan;
    }

    public function render()
    {
        return view('livewire.scan.show-scan');
    }
}
