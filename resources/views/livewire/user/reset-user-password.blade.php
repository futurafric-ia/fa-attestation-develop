<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('backend.users.reset') }}
</x-slot>

<section>
    <x-section-header title="Réinitialisation du mot de passe"></x-section-header>

    <x-form-card submit="resetUser">
        <x-slot name="form">

            <div class="grid grid-cols-8 gap-4">
                <div class="col-span-12 sm:col-span-12">
                    <x-label for="email" value="Adresse E-mail" />
                    <x-input
                        id="email"
                        class="mt-1 block w-full"
                        name="state.email"
                        wire:model.defer="state.email"
                        wire:loading.attr="disabled"
                        wire:target="resetUser"
                    ></x-input>
                    <x-input-error for="state.email" class="mt-2" />
                </div>
                <div class="col-span-12 sm:col-span-12">
                    <x-label for="password" value="Mot de passe" />
                    <x-input
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        name="state.password"
                        wire:model.defer="state.password"
                        wire:loading.attr="disabled"
                        wire:target="resetUser"
                    ></x-input>
                    <x-input-error for="state.password" class="mt-2" />
                </div>
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="resetUser" type="submit">
                Réinitialiser
            </x-loading-button>
        </x-slot>
    </x-form-card>
</section>
