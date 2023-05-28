<?php

namespace Domain\Supply\Actions;

use Domain\Supply\Events\SupplierDeleted;
use Domain\Supply\Models\Supplier;
use Illuminate\Validation\ValidationException;

final class DeleteSupplierAction
{
    public function execute(Supplier $supplier): Supplier
    {
        if ($supplier->supplies()->count()) {
            throw ValidationException::withMessages([
                'deleteSupplierAction' => 'Vous ne pouvez pas supprimer ce fournisseur. Il existe des attestations livrées par ce fournisseur.',
            ]);
        }

        if (null !== $supplier->deleted_at) {
            throw ValidationException::withMessages([
                'deleteSupplierAction' => 'Ce fournisseur est déjà supprimé.',
            ]);
        }

        if (! $supplier->delete()) {
            throw ValidationException::withMessages([
                'deleteSupplierAction' => 'Une erreur est survenu lors de la suppression de ce fournisseur.',
            ]);
        }

        SupplierDeleted::dispatch($supplier);

        return $supplier;
    }
}
