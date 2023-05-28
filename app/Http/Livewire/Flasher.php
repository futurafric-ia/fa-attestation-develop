<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Flasher extends Component
{
    public $listeners = ['flash'];

    public function flash($type, $msg)
    {
        session()->flash($type, $msg);
    }

    public function render()
    {
        return view('livewire.flasher');
    }
}
