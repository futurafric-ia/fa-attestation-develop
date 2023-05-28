
    <x-dialog-modal wire:model="confirmingDepartmentDeletion">
        <x-slot name="title">
            Supprimer le département
        </x-slot>

        <x-slot name="content">
            Etes vous sûr de vouloir supprimer cet département ?
        </x-slot>

        <x-slot name="footer">
            <div class="inline-flex items-center">
                <x-button wire:click="$toggle('confirmingDepartmentDeletion')" wire:loading.attr="disabled" >
                    {{ __('Annuler') }}
                </x-button>

                <x-loading-button class="ml-2 btn-red" wire:click="deleteDepartment" wire:target="deleteDepartment" wire:loading.attr="disabled">
                    {{ __('Confirmer') }}
                </x-loading-button>
            </div>
        </x-slot>
    </x-dialog-modal>
