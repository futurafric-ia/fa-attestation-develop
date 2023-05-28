<?php

namespace App\Http\Livewire\Settings;

use Domain\Broker\Actions\UpdateBrokerAction;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateBrokerInformationForm extends Component
{
    use WithFileUploads;

    /**
     * The broker instance.
     *
     * @var mixed
     */
    public $broker;

    /**
     * The new logo for the broker.
     *
     * @var mixed
     */
    public $logo;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    public function mount($broker)
    {
        $this->broker = $broker;
        $this->state = $broker->withoutRelations()->toArray();
    }

    public function updateBroker(UpdateBrokerAction $action)
    {
        $this->resetErrorBag();
        $this->validate([
            'state.name' => ['required', Rule::unique('brokers', 'name')->ignoreModel($this->broker)],
            'state.email' => ['required', 'email', Rule::unique('brokers', 'email')->ignoreModel($this->broker)],
        ]);

        $action->execute(
            $this->broker,
            $this->logo
            ? array_merge($this->state, ['logo' => $this->logo])
            : $this->state
        );

        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.settings.update-broker-information-form');
    }
}
