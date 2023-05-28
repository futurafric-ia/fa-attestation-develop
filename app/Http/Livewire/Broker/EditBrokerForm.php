<?php

namespace App\Http\Livewire\Broker;

use App\ViewModels\Broker\BrokerFormViewModel;
use Domain\Broker\Actions\UpdateBrokerAction;
use Domain\Broker\Models\Broker;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditBrokerForm extends Component
{
    use AuthorizesRequests;

    public $broker;

    public $state = [
        'code' => null,
        'name' => null,
        'contact' => null,
        'address' => null,
        'department_id' => null,
        'email' => null,
    ];

    public function mount(Broker $broker)
    {
        $this->broker = $broker;
        $this->state = [
            'code' => $this->broker->code,
            'name' => $this->broker->name,
            'contact' => $this->broker->contact,
            'address' => $this->broker->address,
            'department_id' => $this->broker->department_id,
            'email' => $this->broker->email,
        ];
    }

    public function saveBroker(UpdateBrokerAction $updateBrokerAction)
    {
        $this->authorize('update', $this->broker);

        $this->validate([
            'state.code' => ['required', Rule::unique('brokers', 'code')->ignoreModel($this->broker)],
            'state.name' => ['required', Rule::unique('brokers', 'name')->ignoreModel($this->broker)],
            'state.email' => ['required', 'email', Rule::unique('brokers', 'email')->ignoreModel($this->broker)],
            'state.department_id' => ['required', Rule::exists('departments', 'id')],
            'state.address' => ['nullable', 'string', 'max:255'],
            'state.contact' => ['nullable', 'string', 'max:255'],
        ]);

        $updateBrokerAction->execute($this->broker, $this->state);

        session()->flash('success', "L'intermédiaire a été mise à jour avec succès!");

        return redirect()->route('brokers.index');
    }

    public function render()
    {
        return view('livewire.broker.edit-broker-form', new BrokerFormViewModel($this->broker));
    }
}
