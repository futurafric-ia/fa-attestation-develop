<?php

namespace App\Http\Livewire\Broker;

use Domain\Broker\Actions\DeleteBrokerAction;
use Domain\Broker\Actions\RestoreBrokerAction;
use Domain\Broker\Models\Broker;
use Livewire\Component;

class ListBrokers extends Component
{
    public function render()
    {
        return view('livewire.broker.list-brokers');
    }
}
