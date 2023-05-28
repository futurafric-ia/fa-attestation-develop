
    <!-- Delete Broker Confirmation Modal -->
    <x-dialog-modal wire:model="confirmingBrokerDeletion">
        <x-slot name="title">
            Supprimer l'intermédiaire
        </x-slot>

        <x-slot name="content">
                Etes vous sûr de vouloir supprimer ce intermédiaire ? Un fois supprimé, le compte de l'intermediaire et de ses utilisateurs seront désactivés.
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$toggle('confirmingBrokerDeletion')" wire:loading.attr="disabled" >
                {{ __('Annuler') }}
            </x-button>

            <x-loading-button
                class="ml-2 btn-red"
                wire:click="deleteBroker"
                wire:loading.attr="disabled">
                {{ __('Confirmer') }}
            </x-loading-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Restore Broker Confirmation Modal -->
    <x-dialog-modal wire:model="confirmingBrokerRestoration">
        <x-slot name="title">
            Restaurer l'intermédiaire
        </x-slot>

        <x-slot name="content">
            Etes vous sûr de vouloir restaurer ce intermédiaire ?
        </x-slot>

        <x-slot name="footer">
            <x-button wire:click="$toggle('confirmingBrokerRestoration')" wire:loading.attr="disabled">
                {{ __('Annuler') }}
            </x-button>

            <x-loading-button
                class="ml-2 btn-red"
                wire:click="restoreBroker"
                wire:target="restoreBroker"
                wire:loading.attr="disabled">
                {{ __('Confirmer') }}
            </x-loading-button>
        </x-slot>
    </x-dialog-modal>
