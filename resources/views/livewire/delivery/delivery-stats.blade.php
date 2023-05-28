<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('delivery.reports') }}
</x-slot>

<section x-data="{ showingDeliveryAttestations: @entangle('showingDeliveryAttestations') }">
    <x-section-header title="Statistique des livraisons"/>

    <div x-on:apply-filters.window="$wire.applyFilter($event.detail)" class="mb-4">
        <x-advanced-filter :filter-groups="[
            [
                'name' => 'Livraison',
                'filters' => [
                    ['title' => 'Date de livraison', 'name' => 'delivered_at', 'type' => 'datetime'],
                    ['title' => 'Quantité livrée', 'name' => 'quantity', 'type' => 'numeric'],
                ]
            ],
            [
                'name' => 'Intermédiaire',
                'filters' => [
                    ['title' => 'Code intermédiaire', 'name' => 'broker.code', 'type' => 'string'],
                    ['title' => 'Nom intermédiaire', 'name' => 'broker.name', 'type' => 'string']
                ]
            ]
        ]"></x-advanced-filter>
    </div>

    <livewire:delivery.delivery-stats-table/>

    <div
        x-show="showingDeliveryAttestations"
        x-on:close.stop="showingDeliveryAttestations = false; $wire.close()"
        x-on:keydown.escape.window="showingDeliveryAttestations = false; $wire.close()"
        x-cloak
    >
        <div class="modal flex items-center justify-center fixed w-full h-full top-0 left-0 z-40">
            <div
                x-show="showingDeliveryAttestations"
                class="modal-overlay absolute w-full h-full bg-gray-50"
                x-on:click="showingDeliveryAttestations = false; $wire.close()"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
            </div>

            <div
                x-show="showingDeliveryAttestations"
                class="modal-container fixed w-full h-full z-50 overflow-y-auto"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div x-on:click="showingDeliveryAttestations = false; $wire.close()"
                     class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-gray text-sm z-50">
                    <svg class="fill-current text-gray" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                         viewBox="0 0 18 18">
                        <path
                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                    </svg>
                    (Fermer)
                </div>

                <div class="modal-content mx-auto h-auto text-left p-4">
                    <div class="px-6 py-4">
                        <h3 class="text-2xl font-bold text-center">
                            @if ($selectedDelivery)
                                Utilisation des documents de la livraison {{ $selectedDelivery->code }}
                            @endif
                        </h3>

                        <div class="mt-4">
                            <div wire:loading wire:target="displaySlideover">
                                <div class="animate-pulse flex space-x-4">
                                    <div class="flex-1 space-y-4 py-1">
                                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                        <div class="space-y-2">
                                            <div class="h-4 bg-gray-200 rounded"></div>
                                            <div class="h-4 bg-red-200 rounded w-5/6"></div>
                                            <div class="h-4 bg-gray-200 rounded"></div>
                                            <div class="h-4 bg-gray-200 rounded"></div>
                                            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                                            <div class="h-4 bg-gray-200 rounded"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($selectedDelivery)
                                <div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-col text-base">
                                            <div>
                                                <span class="font-semibold">Code:</span> {{ $selectedDelivery->broker->code }}
                                            </div>
                                            <div>
                                                <span class="font-semibold">Raison social:</span> {{ $selectedDelivery->broker->name }}
                                            </div>
                                            <div>
                                                <span class="font-semibold">Date de livraison:</span> {{ $selectedDelivery->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <div class="actions flex self-start space-x-2">
                                            <x-secondary-button >
                                                Imprimer stock
                                            </x-secondary-button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <livewire:delivery.delivery-attestations-table :delivery="$selectedDelivery"/>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
