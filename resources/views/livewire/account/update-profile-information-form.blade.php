<x-form-section submit="updateProfile">
    <x-slot name="title">
        Détails du Profil
    </x-slot>

    <x-slot name="description">
        Mise à jour du profil et de l'adresse E-mail.
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-8">
            <x-label for="last_name" value="Nom" />
            <x-input id="last_name" name="state.last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name" />
            <x-input-error for="state.last_name" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="col-span-6 sm:col-span-8">
            <x-label for="first_name" value="Prénoms" />
            <x-input id="first_name" name="state.first_name" type="text" class="mt-1 block w-full" wire:model.defer="state.first_name" />
            <x-input-error for="state.first_name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-8">
            <x-label for="email" value="E-mail" />
            <x-input id="email" name="state.email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-input-error for="state.email" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-8">
            <x-label for="address" value="Adresse" />
            <x-input id="address" name="state.address" type="text" class="mt-1 block w-full" wire:model.defer="state.address" />
            <x-input-error for="state.address" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-8">
            <x-label for="contact" value="Contact" />
            <x-input id="contact" name="state.contact" type="text" class="mt-1 block w-full" wire:model.defer="state.contact" />
            <x-input-error for="state.contact" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            Enregistré.
        </x-action-message>

        <x-loading-button class="btn-primary" type="submit" wire:loading.class="opacity-25" wire:loading.attr="disabled">
            Enregistrer
        </x-loading-button>
    </x-slot>
</x-form-section>
