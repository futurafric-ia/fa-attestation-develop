<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('attestation.anterior.index') }}
</x-slot>

<section x-data="{ displayingSlideover: @entangle('displayingSlideover') }" class="overflow-hidden">

    <x-section-header title="Attestations antérieures"></x-section-header>

    <livewire:attestation.anterior-attestations-table />

    <div class="absolute inset-0 overflow-hidden" x-show="displayingSlideover" x-cloak>
      <div x-show="displayingSlideover" x-transition:enter="ease-in-out duration-500"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="ease-in-out duration-500"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <section class="absolute inset-y-0 right-0 pl-10 max-w-full flex">
            <div x-show="displayingSlideover" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
x-transition:enter-start="translate-x-full"
x-transition:enter-end="translate-x-0"
x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
x-transition:leave-start="translate-x-0"
x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                <div class="h-full flex flex-col space-y-6 py-6 bg-white shadow-xl overflow-y-scroll">
                    <header class="px-4 sm:px-6">
                        <div class="flex items-start justify-between space-x-3">
                            <h2 class="text-xl leading-7 font-medium text-gray-900">Détails de l'attestation</h2>
                            <div class="h-7 flex items-center">
                                <button @click="displayingSlideover = false" aria-label="Close panel" class="text-gray-400 hover:text-gray-500 transition ease-in-out duration-150">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </header>
                    <div class="relative flex-1 px-4 sm:px-6">
                        @if ($attestationBeingDisplayed)
                        <x-tabs :navs="['Informations', 'Livraisons', 'Scans']">
                            <x-tab id="Informations">
                                <div class="px-6 py-5 flex flex-col space-y-2">
                                    <div>
                                        <x-label value="Image de l'attestation" />
                                        @if ($attestationBeingDisplayed->image_url)
                                            <img src="{{ $attestationBeingDisplayed->image_url }}" alt="{{ $attestationBeingDisplayed->attestation_number }}" class="rounded-sm object-cover min-h-full min-w-full inline-block align-middle mt-2">
                                        @else
                                        <span class="text-gray-700 text-sm">
                                            Aucune image disponible
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <x-label value="Numéro d'attestation" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $attestationBeingDisplayed->attestation_number }}
                                        </span>
                                    </div>
                                    <div>
                                        <x-label value="Nom de l'assuré" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $attestationBeingDisplayed->insured_name ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div>
                                        <x-label value="Immatriculation" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $attestationBeingDisplayed->matriculation ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div>
                                        <x-label value="Numéro de police" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $attestationBeingDisplayed->police_number ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div>
                                        <x-label value="Addresse" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $attestationBeingDisplayed->address ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div>
                                        <x-label value="Marque et type du vehicule" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $attestationBeingDisplayed->make ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div>
                                        <x-label value="Date de début" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $attestationBeingDisplayed->start_date ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div>
                                        <x-label value="Date de fin" />
                                        <span class="text-gray-700 text-sm">
                                            {{ $attestationBeingDisplayed->end_date ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div>
                                        <x-label value="Statut" />
                                        <span class="text-gray-700 text-sm">
                                            <x-badge class="{{ $attestationBeingDisplayed->state->textColor() }} {{ $attestationBeingDisplayed->state->color() }}">
                                                {{ $attestationBeingDisplayed->state->label() }}
                                            </x-badge>
                                        </span>
                                    </div>
                                </div>
                            </x-tab>
                            <x-tab id="Livraisons">
                                <div class="px-6 py-5">
                                    @if ($attestationBeingDisplayed->deliveries->isEmpty())
                                    <span class="text-gray-700 text-sm block text-center">
                                        Aucune livraison effectuée
                                    </span>
                                    @endif
                                    @foreach ($attestationBeingDisplayed->deliveries as $delivery)
                                    <span class="text-gray-700 text-sm mb-2">
                                        Une livraison a été effectuée le {{ $delivery->delivered_at->format('d/m/Y') }} à {{ $delivery->broker->name }}
                                    </span>
                                    @endforeach
                                </div>
                            </x-tab>
                            <x-tab id="Scans">
                                <div class="px-6 py-5">
                                    @if ($attestationBeingDisplayed->scans->isEmpty())
                                    <span class="text-gray-700 text-sm block text-center">
                                        Aucun scan effectué
                                    </span>
                                    @endif
                                    @foreach ($attestationBeingDisplayed->scans as $scan)
                                        <span class="text-gray-700 text-sm mb-2">
                                            Un scan a été effectuée le {{ $scan->created_at->format('d/m/Y') }} avec le statut
                                            <x-badge class="{{ $attestationBeingDisplayed->state->textColor() }} {{ $attestationBeingDisplayed->state->color() }}">
                                                {{ $attestationBeingDisplayed->state->label() }}
                                            </x-badge>
                                        </span>
                                    @endforeach
                                </div>
                            </x-tab>
                        </x-tabs>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
