<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('suppliers.create') }}
</x-slot>

<section>
    <x-section-header>
        <x-slot name="title">
            {{ __('Créer un fournisseur') }}
        </x-slot>
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="suppliers.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="h-6 w-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-form-card submit="saveSupplier">
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-4">

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="name" value="Nom" />
                    <x-input
                        id="name"
                        class="mt-1 block w-full"
                        name="state.name"
                        wire:model.defer="state.name"
                        wire:loading.attr="disabled"
                        wire:target="saveSupplier"
                    ></x-input>
                    <x-input-error for="state.name" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="code" value="Code" />
                    <x-input
                        id="code"
                        class="mt-1 block w-full"
                        name="state.code"
                        wire:model.defer="state.code"
                        wire:loading.attr="disabled"
                        wire:target="saveSupplier"
                    ></x-input>
                    <x-input-error for="state.code" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="type" value="Type" />
                    <x-input
                        id="type"
                        class="mt-1 block w-full"
                        name="state.type"
                        wire:model.defer="state.type"
                        wire:loading.attr="disabled"
                        wire:target="saveSupplier"
                    ></x-input>
                    <x-input-error for="state.type" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="email" value="Adresse E-mail" />
                    <x-input
                        id="email"
                        class="mt-1 block w-full"
                        name="state.email"
                        wire:model.defer="state.email"
                        wire:loading.attr="disabled"
                        wire:target="saveSupplier"
                    ></x-input>
                    <x-input-error for="state.email" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="contact" value="Contact" />
                    <x-input
                        id="contact"
                        class="mt-1 block w-full"
                        name="state.contact"
                        wire:model.defer="state.contact"
                        wire:loading.attr="disabled"
                        wire:target="saveSupplier"
                    ></x-input>
                    <x-input-error for="state.contact" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="address" value="Adresse" />
                    <x-input
                        id="address"
                        class="mt-1 block w-full"
                        name="state.address"
                        wire:model.defer="state.address"
                        wire:loading.attr="disabled"
                        wire:target="saveSupplier"
                    ></x-input>
                    <x-input-error for="state.address" class="mt-2" />
                </div>

            </div>
        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="saveSupplier" type="submit">
                Enregistrer
            </x-loading-button>
        </x-slot>
    </x-form-card>
</section>
