<?php

namespace App\Http\Livewire\Supplier;

use Domain\Supply\Actions\CreateSupplierAction;
use Domain\Supply\Models\Supplier;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateSupplierForm extends Component
{
    use AuthorizesRequests;

    public $state = [
        'name' => null,
        'code' => null,
        'email' => null,
        'type' => null,
        'contact' => null,
        'address' => null,
    ];

    public function saveSupplier(CreateSupplierAction $createSupplierAction)
    {
        $this->authorize('create', Supplier::class);

        $this->validate([
            'state.code' => ['required', Rule::unique('suppliers', 'code')],
            'state.email' => ['required', 'email', Rule::unique('suppliers', 'email')],
            'state.name' => ['required', 'string', 'max:255'],
            'state.type' => ['nullable', 'string', 'max:255'],
            'state.contact' => ['nullable', 'string', 'max:255'],
            'state.address' => ['nullable', 'string', 'max:255'],
        ]);

        $createSupplierAction->execute($this->state);

        session()->flash('success', 'Le fournisseur à été crée avec succès!');

        return redirect()->route('suppliers.index');
    }

    public function render()
    {
        return view('livewire.supplier.create-supplier-form');
    }
}
