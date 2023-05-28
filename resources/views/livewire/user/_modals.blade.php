    <!-- Delete User Confirmation Modal -->
    <x-dialog-modal wire:model="confirmingUserDeletion">
        <x-slot name="title">
            Supprimer l'utilisateur
        </x-slot>

        <x-slot name="content">
            Etes vous sûr de vouloir supprimer cet utilisateur ?
        </x-slot>

        <x-slot name="footer">
            <div class="inline-flex items-center">
                <x-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled" >
                    {{ __('Annuler') }}
                </x-button>

                <x-loading-button class="ml-2 btn-red" wire:click="deleteUser" wire:target="deleteUser" wire:loading.attr="disabled">
                    {{ __('Confirmer') }}
                </x-loading-button>
            </div>
        </x-slot>
    </x-dialog-modal>

    <!-- Active User Confirmation Modal -->
    <x-dialog-modal wire:model="confirmingUserRestoration">
        <x-slot name="title">
            Restaurer l'utilisateur
        </x-slot>

        <x-slot name="content">
            Etes vous sûr de vouloir restaurer cet utilisateur ?
        </x-slot>

        <x-slot name="footer">
            <div class="inline-flex items-center">
                <x-button wire:click="$toggle('confirmingUserRestoration')" wire:loading.attr="disabled" >
                    {{ __('Annuler') }}
                </x-button>

                <x-loading-button class="btn-red ml-2" wire:click="restoreUser" wire:target="restoreUser" wire:loading.attr="disabled">
                    {{ __('Confirmer') }}
                </x-loading-button>
            </div>
        </x-slot>
    </x-dialog-modal>
