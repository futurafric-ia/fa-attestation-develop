<?php

namespace App\Http\Livewire\Supplier;

use Domain\Supply\Actions\DeleteSupplierAction;
use Domain\Supply\Models\Supplier;
use Livewire\Component;

class ListSuppliers extends Component
{
    public function render()
    {
        return view('livewire.supplier.list-suppliers');
    }
}
