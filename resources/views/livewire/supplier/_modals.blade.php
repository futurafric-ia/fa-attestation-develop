
    <!-- Delete Supplier Confirmation Modal -->
    <x-dialog-modal wire:model="confirmingSupplierDeletion">
        <x-slot name="title">
            Supprimer le fournisseur
        </x-slot>

        <x-slot name="content">
            <p>Etes vous sûr de vouloir supprimer ce fournisseur ?</p>
            @error('deleteSupplierAction')
                <p class="text-red-500 text-xs italic">{{ $message }}</p>
            @enderror
        </x-slot>

        <x-slot name="footer">
            <x-button  wire:click="$toggle('confirmingSupplierDeletion')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </x-button>

            <x-loading-button
                class="ml-2 btn-red"
                wire:click="deleteSupplier"
                wire:target="deleteSupplier"
                wire:loading.attr="disabled">
                {{ __('Confirmer') }}
            </x-loading-button>
        </x-slot>
    </x-dialog-modal>
