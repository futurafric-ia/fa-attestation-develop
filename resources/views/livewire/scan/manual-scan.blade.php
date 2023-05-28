<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('scan.manual') }}
</x-slot>

<section>

    <x-section-header title="Saisie manuelle">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="scan.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <div class="mb-4">
        <x-validation-errors />
    </div>

    <x-form-card submit="runScan">
        <x-slot name="form">
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">

                <div class="pr-6 pb-8 w-full {{ $canChooseAttestationState ? 'lg:w-1/3' : 'lg:w-1/2' }}">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="broker_id" value="Intermédiaire" />
                        <div class="mt-1">
                            {{-- <livewire:broker.broker-autocomplete :initial-data="$brokers->toArray()" /> --}}
                            <livewire:broker.broker-select name="broker_id" placeholder="Choississez un intermediaire"
                                :searchable="true" />

                        </div>
                        <x-input-error for="state.broker_id" class="mt-2" />
                    </div>
                </div>

                @if ($canChooseAttestationState)
                    <div class="pr-6 pb-8 w-full lg:w-1/3">
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="attestation_state" value="Statut" />
                            <x-select wire:loading.attr="disabled" wire:target="runScan" class="mt-1 block w-full"
                                name="state.attestation_state" id="attestation_state"
                                wire:model="state.attestation_state" label="" first-option="Choisissez un statut"
                                :options="$attestationStates->toArray()">
                            </x-select>
                            <x-input-error for="state.attestation_state" class="mt-2" />
                        </div>
                    </div>
                @endif

                <div class="pr-6 pb-8 w-full {{ $canChooseAttestationState ? 'lg:w-1/3' : 'lg:w-1/2' }}">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="attestation_type_id" value="Type d'imprimés" />
                        <x-select wire:loading.attr="disabled" wire:target="runScan" class="mt-1 block w-full"
                            name="state.attestation_type_id" id="attestation_type_id"
                            wire:model="state.attestation_type_id" first-option="Choisissez un type d'imprimés"
                            :options="$attestationTypes->toArray()">
                        </x-select>
                        <x-input-error for="state.attestation_type_id" class="mt-2" />
                    </div>
                </div>

                <livewire:attestation.attestation-ranges :ranges="$items" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" type="submit" wire:target="runScan">
                Démarrer le scan
            </x-loading-button>
        </x-slot>
    </x-form-card>

</section>
