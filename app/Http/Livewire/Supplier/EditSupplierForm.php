<?php

namespace App\Http\Livewire\Supplier;

use Domain\Supply\Actions\UpdateSupplierAction;
use Domain\Supply\Models\Supplier;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditSupplierForm extends Component
{
    use AuthorizesRequests;

    public $supplier;

    public $state = [
        'name' => null,
        'code' => null,
        'email' => null,
        'type' => null,
        'contact' => null,
        'address' => null,
    ];

    public function mount(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->state = [
            'name' => $this->supplier->name,
            'code' => $this->supplier->code,
            'email' => $this->supplier->email,
            'type' => $this->supplier->type,
            'contact' => $this->supplier->contact,
            'address' => $this->supplier->address,
        ];
    }

    public function saveSupplier(UpdateSupplierAction $updateSupplierAction)
    {
        $this->authorize('update', $this->supplier);

        $this->validate([
            'state.code' => ['required', 'string', Rule::unique('suppliers', 'code')->ignoreModel($this->supplier)],
            'state.email' => ['required', 'email', Rule::unique('suppliers', 'email')->ignoreModel($this->supplier)],
            'state.name' => ['required', 'string', 'max:255'],
            'state.type' => ['nullable', 'string', 'max:255'],
            'state.contact' => ['nullable', 'string', 'max:255'],
            'state.address' => ['nullable', 'string', 'max:255'],
        ]);

        $updateSupplierAction->execute($this->supplier, $this->state);

        session()->flash('success', 'Le fournisseur à été mise à jour avec succès!');

        return redirect()->route('suppliers.index');
    }

    public function render()
    {
        return view('livewire.supplier.edit-supplier-form');
    }
}
