
     <!-- Delete Broker Confirmation Modal -->
     <x-dialog-modal wire:model="confirmingAttestationTypeDeletion">
        <x-slot name="title">
            Supprimer un type imprimé
        </x-slot>

        <x-slot name="content">
            Etes vous sûr de vouloir supprimer ce type d'imprimé ? Un fois supprimé, ce type d'imprimé ne sera plus accessible sur la plateforme.
        </x-slot>

        <x-slot name="footer">
            <div class="inline-flex items-center">
                <x-button wire:click="$toggle('confirmingAttestationTypeDeletion')" wire:loading.attr="disabled" >
                    {{ __('Annuler') }}
                </x-button>

                <x-loading-button wire:target="deleteAttestationType" class="ml-2 btn-red" wire:click="deleteAttestationType" wire:loading.attr="disabled">
                    {{ __('Confirmer') }}
                </x-loading-button>
            </div>
        </x-slot>
    </x-dialog-modal>
