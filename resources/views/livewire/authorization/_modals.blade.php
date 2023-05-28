     <!-- Delete Broker Confirmation Modal -->
     <x-dialog-modal wire:model="confirmingRoleDeletion">
        <x-slot name="title">
            Supprimer un rôle
        </x-slot>

        <x-slot name="content">
            Etes vous sûr de vouloir supprimer ce rôle ? Un fois supprimé, ce rôle ne sera plus accessible sur la plateforme.
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$toggle('confirmingRoleDeletion')" wire:loading.attr="disabled" >
                {{ __('Annuler') }}
            </x-button>

            <x-loading-button class="ml-2 btn-secondary" wire:click="deleteRole" wire:loading.attr="disabled">
                {{ __('Confirmer') }}
            </x-loading-button>
        </x-slot>
    </x-dialog-modal>
