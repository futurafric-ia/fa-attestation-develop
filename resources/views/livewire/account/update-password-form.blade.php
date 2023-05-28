<x-form-section submit="updatePassword">
    <x-slot name="title">
        Changement du mot de passe
    </x-slot>

    <x-slot name="description">
        Sécurisez votre compte en choisissant un mot de passe long et aléatoire.
    </x-slot>

    <x-slot name="form">
        <div class="col-span-8 sm:col-span-8">
            <x-label for="current_password" value="Mot de passe actuel" />
            <x-input id="current_password" type="password" class="mt-1 block w-full" wire:model="state.current_password" />
            <x-input-error for="state.current_password" class="mt-2" />
        </div>

        <div class="col-span-8 sm:col-span-8">
            <x-label for="password" value="Nouveau mot de passe" />
            <x-input id="password" type="password" class="mt-1 block w-full" wire:model="state.password" />
            <x-input-error for="state.password" class="mt-2" />
        </div>

        <div class="col-span-8 sm:col-span-8">
            <x-label for="password_confirmation" value="Retapez le mot de passe" />
            <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="state.password_confirmation" />
            <x-input-error for="state.password_confirmation" class="mt-2" />
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
