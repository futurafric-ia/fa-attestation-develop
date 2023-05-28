<?php

namespace App\Http\Livewire\Broker;

use App\ViewModels\Broker\BrokerFormViewModel;
use Domain\Broker\Actions\CreateBrokerAction;
use Domain\Broker\Models\Broker;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Component;

class CreateBrokerForm extends Component
{
    use AuthorizesRequests;

    public $shareCredentialsWithOwner = false;

    public $state = [
        'code' => null,
        'name' => null,
        'contact' => null,
        'address' => null,
        'department_id' => null,
        'email' => null,
        'owner' => [
            'first_name' => null,
            'last_name' => null,
            'email' => null,
            'contact' => null,
            'address' => null,
        ],
    ];

    public function saveBroker(CreateBrokerAction $createBrokerAction)
    {
        $this->authorize('create', Broker::class);

        $this->validate([
            'state.code' => ['required', Rule::unique('brokers', 'code')],
            'state.name' => ['required', 'string', 'max:255', Rule::unique('brokers', 'name')],
            'state.email' => ['required', 'email', Rule::unique('brokers', 'email')],
            'state.department_id' => ['required', Rule::exists('departments', 'id')],
            'state.address' => ['nullable', 'string', 'max:255'],
            'state.contact' => ['nullable', 'string', 'max:255'],
            'state.owner.first_name' => ['required', 'string', 'max:255'],
            'state.owner.last_name' => ['required', 'string', 'max:255'],
            'state.owner.address' => ['nullable', 'string', 'max:255'],
            'state.owner.contact' => ['nullable', 'string', 'max:255'],
            'state.owner.email' => [
                new RequiredIf($this->shareCredentialsWithOwner === false),
                'nullable',
                'email',
                Rule::unique('users', 'email'),
            ],
        ]);

        $createBrokerAction->execute($this->state);

        session()->flash('success', "L'intermédiaire a été crée avec succès!");

        return redirect()->route('brokers.index');
    }

    public function render()
    {
        return view('livewire.broker.create-broker-form', new BrokerFormViewModel());
    }
}
