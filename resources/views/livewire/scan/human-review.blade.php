<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('scan.mismatches.show.humanReview', $scan) }}
</x-slot>

<section>
    <x-section-header title="Révue humaine">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" href="{{ route('scan.show.attestations.index', $scan) }}">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <validation-errors field="attestation"></validation-errors>

    <x-action-message on="saved" timeout="3000">
        <x-alert class="alert-success">
            Les modifications ont été bien enregistrées.
        </x-alert>
    </x-action-message>

    @if ($itemBeingReviewed)
        <x-card>
            <form wire:submit.prevent="save">
                <div id="img-zoom"></div>

                <div class="flex justify-between">
                    <div class="px-2 py-5 self-start" id="img-container" style="width: 500px; height:500px;">
                        <img src="{{ $itemBeingReviewed->image_url }}" alt="{{ $itemBeingReviewed->attestation_number }}" class="rounded-sm object-cover min-h-full min-w-full inline-block align-middle">
                    </div>
                    <div class="w-2/3 p-4">
                        <div class="grid grid-cols-8 gap-4">

                            <div class="col-span-4 sm:col-span-4">
                                <div>
                                    <x-label for="attestation_number" value="Numéro de l'attestation" />
                                    <x-input
                                        name="state.attestation_number"
                                        type="number"
                                        class="w-full"
                                        id="attestation_number"
                                        wire:model.defer="state.attestation_number"
                                    />
                                    <x-input-error for="state.attestation_number" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-span-4 sm:col-span-4">
                                <div>
                                    <x-label for="insured_name" value="Nom de l'assuré" />
                                    <x-input
                                        name="state.insured_name"
                                        class="w-full"
                                        id="insured_name"
                                        wire:model.defer="state.insured_name"
                                    />
                                    <x-input-error for="state.insured_name" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-span-4 sm:col-span-4">
                                <div>
                                    <x-label for="matriculation" value="Immatriculation" />
                                    <x-input
                                        type="text"
                                        name="state.matriculation"
                                        class="w-full"
                                        id="matriculation"
                                        wire:model.defer="state.matriculation"
                                    />
                                    <x-input-error for="state.matriculation" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-span-4 sm:col-span-4">
                                <div>
                                    <x-label for="police_number" value="Numéro de police" />
                                    <x-input
                                        name="state.police_number"
                                        class="w-full"
                                        id="police_number"
                                        wire:model.defer="state.police_number"
                                    />
                                    <x-input-error for="state.police_number" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-span-4 sm:col-span-4">
                                <div>
                                    <x-label for="address" value="Adresse" />
                                    <x-input
                                        name="state.address"
                                        class="w-full"
                                        id="address"
                                        wire:model.defer="state.address"
                                    />
                                    <x-input-error for="state.address" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-span-4 sm:col-span-4">
                                <div>
                                    <x-label for="make" value="Marque et type" />
                                    <x-input
                                        name="state.make"
                                        class="w-full"
                                        id="make"
                                        wire:model.defer="state.make"
                                    />
                                    <x-input-error for="state.make" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-span-4 sm:col-span-4">
                                <div>
                                    <x-label for="start_date" value="Date de début" />
                                    <x-input
                                        name="state.start_date"
                                        type="date"
                                        class="w-full"
                                        id="start_date"
                                        wire:model.defer="state.start_date"
                                    />
                                    <x-input-error for="state.start_date" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-span-4 sm:col-span-4">
                                <div>
                                    <x-label for="end_date" value="Date de fin" />
                                    <x-input
                                        name="state.end_date"
                                        type="date"
                                        class="w-full"
                                        id="end_date"
                                        wire:model.defer="state.end_date"
                                    />
                                    <x-input-error for="state.end_date" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="flex items-center justify-end px-4 py-3 space-x-2 bg-gray-50 text-right sm:px-6"
                    x-data="{
                        canGoNext: '{{$canGoNext}}',
                        canGoBack: '{{$canGoBack}}',
                        init() {
                            this.initZoomView()
                        },
                        initZoomView() {
                            new ImageZoom(document.querySelector('#img-container'), {
                                width: 500,
                                zoomWidth: 500,
                                offset: {vertical: 0, horizontal: 10}
                            })
                        }
                    }"
                    x-init="init"
                >
                    <x-loading-button class="btn-white" wire:click="goToPrevious" wire:target="goToPrevious" wire:loading.attr="disabled" x-bind:class="{'opacity-75 cursor-not-allowed': !canGoBack}" x-bind:disabled="!canGoBack">
                        Précédent
                    </x-loading-button>
                    <x-loading-button class="btn-white" wire:click="goToNext" wire:target="goToNext" wire:loading.attr="disabled" x-bind:disabled="!canGoNext" x-bind:class="{'opacity-75 cursor-not-allowed': !canGoNext}" x-on:click="initZoomView">
                        Suivant
                    </x-loading-button>
                    <x-loading-button class="btn-primary" type="submit" wire:target="save">
                        Enregistrer
                    </x-loading-button>
                </div>
            </form>
        </x-card>
    @else
        <x-alert class="alert-warning" title="Liste vide">
            Il n'y a aucun élément à corriger !
        </x-alert>
    @endif
</section>

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/js-image-zoom/js-image-zoom.min.js"></script>
@endpush
