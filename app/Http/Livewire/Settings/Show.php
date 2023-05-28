<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;

class Show extends Component
{
    public $broker;

    public function mount()
    {
        $this->broker = broker();
    }

    public function render()
    {
        return view('livewire.settings.show');
    }
}
