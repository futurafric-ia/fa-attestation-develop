<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('backend.imprimes.edit', $attestationType) }}
</x-slot>

<section>
    <x-section-header title="Editer un type d'attestation">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="backend.imprimes.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-form-card submit="saveAttestationType">
        <x-slot name="form">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6 sm:col-span-12">
                    <x-label for="name" value="Libelle du type d'attestation" />
                    <x-input
                        id="name"
                        class="mt-1 block w-full"
                        name="state.name"
                        type="text"
                        wire:model.defer="state.name"
                        wire:loading.attr="disabled"
                        wire:target="saveAttestationType"
                    >
                    </x-input>
                    <x-input-error for="state.name" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-12">
                    <x-label for="color" value="Couleur du type d'attestation" />
                    <x-color-picker class="mt-1 block w-full" wire:model.defer="state.color" color="{{ $state['color'] }}" />
                    <x-input-error for="state.color" class="mt-2" />
                </div>

                <div class="col-span-12 sm:col-span-12">
                    <x-label for="description" value="Veuillez entrer une description relative au département:" />
                    <x-textarea
                        id="description"
                        name="state.description"
                        wire:model.defer="state.description"
                        wire:loading.attr="disabled"
                        wire:model.defer="state.description"
                    >
                    </x-textarea>
                    <x-input-error for="state.description" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="saveAttestationType" type="submit">
                Soumettre
            </x-loading-button>
        </x-slot>
    </x-form-card>
</section>
