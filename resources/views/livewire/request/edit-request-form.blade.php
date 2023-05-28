<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('request.edit', $request) }}
</x-slot>

<section>
    <x-section-header title="Modifier ma demande">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="request.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-form-card submit="saveRequest">
        <x-slot name="form">
            <div class="grid grid-cols-9 gap-4">

                <div class="col-span-3 sm:col-span-3">
                    <x-label for="attestation_type" value="Type d'imprimés" />
                    <x-select
                        id="attestation_type"
                        name="state.attestation_type_id"
                        label=""
                        class="mt-1 block w-full"
                        first-option="Choisissez le type d'imprimés"
                        :options="$attestationTypes->toArray()"
                        wire:model.defer="state.attestation_type_id"
                        wire:loading.attr="disabled"
                        wire:target="saveRequest"
                    >
                    </x-select>
                    <x-input-error for="state.attestation_type_id" class="mt-2" />
                </div>

                <div class="col-span-3 sm:col-span-3">
                    <x-label for="quantity" value="Quantité" />
                    <x-input
                        id="quantity"
                        class="mt-1 block w-full"
                        name="state.quantity"
                        type="number"
                        wire:model.defer="state.quantity"
                        wire:loading.attr="disabled"
                        wire:target="saveRequest"
                    >
                    </x-input>
                    <x-input-error for="state.quantity" class="mt-2" />
                </div>

                <div class="col-span-3 sm:col-span-3">
                    <x-label for="expected_at" value="Délai de livraison" />
                    <x-input
                        id="expected_at"
                        class="mt-1 block w-full"
                        name="state.expected_at"
                        type="date"
                        wire:model.defer="state.expected_at"
                        wire:loading.attr="disabled"
                        wire:target="saveRequest"
                    ></x-input>
                    <x-input-error for="state.expected_at" class="mt-2" />
                </div>

                <div class="col-span-9 sm:col-span-9">
                    <x-label for="notes" value="Veuillez entrer toute observation relative à la demande:" />
                    <x-textarea
                        id="notes"
                        name="state.notes"
                        wire:model.defer="state.notes"
                        wire:loading.attr="disabled"
                        wire:model.defer="state.notes"
                    >
                    </x-textarea>
                    <x-input-error for="state.notes" class="mt-2" />
                </div>

            </div>
        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="saveRequest" type="submit">
                Enregistrer
            </x-loading-button>
        </x-slot>
    </x-form-card>
</section>
