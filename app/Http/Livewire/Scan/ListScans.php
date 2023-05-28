<?php

namespace App\Http\Livewire\Scan;

use Livewire\Component;

class ListScans extends Component
{
    public $openModal = false;

    public function open()
    {
    }

    public function render()
    {
        return view('livewire.scan.list-scans');
    }
}
