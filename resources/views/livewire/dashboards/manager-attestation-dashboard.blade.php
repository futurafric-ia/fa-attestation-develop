<div>
    <div class="p-5 shadow bg-white rounded mb-5">
        <x-label value="Type d'attestation" class="my-2 text-base" />
        <x-select
            id="attestationType"
            class="mt-1 block w-full"
            name="attestationType"
            first-option="Toutes les attestations"
            :options="$attestationTypes"
            wire:model="attestationType"
        ></x-select>
        @if ($attestationType)
            <x-button wire:click="clearAttestationTypeSelection" class="mt-2">Effacer</x-button>
        @endif
    </div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800">Vue d'ensemble</h2>
    </div>
    <div class="mt-2">
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
            <x-count-card
                title="Disponible"
                subtitle="{{ $totalAvailableStock }}"
                bg-color="bg-blue-500"
                text-color="text-blue-500"
                link="{{ route('attestation.index', ['filters' => ['status' => \Domain\Attestation\States\Available::class]]) }}"
            >
                <x-slot name="icon">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0z"/><path d="M21 9.5v3c0 2.485-4.03 4.5-9 4.5s-9-2.015-9-4.5v-3c0 2.485 4.03 4.5 9 4.5s9-2.015 9-4.5zm-18 5c0 2.485 4.03 4.5 9 4.5s9-2.015 9-4.5v3c0 2.485-4.03 4.5-9 4.5s-9-2.015-9-4.5v-3zm9-2.5c-4.97 0-9-2.015-9-4.5S7.03 3 12 3s9 2.015 9 4.5-4.03 4.5-9 4.5z"/>
                    </svg>
                </x-slot>
            </x-count-card>

            <x-count-card
                title="Attribuées"
                subtitle="{{ $totalAvailableStockByState[\Domain\Attestation\States\Attributed::$name] ?? 0 }}"
                bg-color="bg-accent-700"
                text-color="text-accent-700"
                link="{{ route('attestation.index', ['filters' => ['status' => \Domain\Attestation\States\Attributed::class]]) }}"
            >
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="server w-8 h-8">
                        <path fill="none" d="M0 0h24v24H0z"/><path d="M16 20V4H4v15a1 1 0 0 0 1 1h11zm3 2H5a3 3 0 0 1-3-3V3a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v7h4v9a3 3 0 0 1-3 3zm-1-10v7a1 1 0 0 0 2 0v-7h-2zM6 6h6v6H6V6zm2 2v2h2V8H8zm-2 5h8v2H6v-2zm0 3h8v2H6v-2z"/>
                    </svg>
                </x-slot>
            </x-count-card>

            <x-count-card
                title="Utilisées"
                subtitle="{{ $totalAvailableStockByState[\Domain\Attestation\States\Used::$name] ?? 0 }}"
                bg-color="bg-accent-900"
                text-color="text-accent-900"
                link="{{ route('attestation.index', ['filters' => ['status' => \Domain\Attestation\States\Used::class]]) }}"
            >
                <x-slot name="icon">
                    <svg class="w-8 h-8" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            viewBox="0 0 24 24" stroke="currentColor">
                        <path fill-rule="evenodd" d="M5 13l4 4L19 7"></path>
                    </svg>
                </x-slot>
            </x-count-card>

            <x-count-card
                title="Annulées"
                subtitle="{{ $totalAvailableStockByState[\Domain\Attestation\States\Cancelled::$name] ?? 0 }}"
                bg-color="bg-secondary-800"
                text-color="text-secondary-800"
                link="{{ route('attestation.index', ['filters' => ['status' => \Domain\Attestation\States\Cancelled::class]]) }}"
            >
                <x-slot name="icon">
                    <svg class="w-8 h-8" fill="currentColor" stroke-linecap="round" stroke-width="2" stroke-linejoin="round">
                        <path fill="none" d="M0 0h24v24H0z"/><path d="M17 6h5v2h-2v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8H2V6h5V3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3zm1 2H6v12h12V8zm-4.586 6l1.768 1.768-1.414 1.414L12 15.414l-1.768 1.768-1.414-1.414L10.586 14l-1.768-1.768 1.414-1.414L12 12.586l1.768-1.768 1.414 1.414L13.414 14zM9 4v2h6V4H9z"/>
                    </svg>
                </x-slot>
            </x-count-card>
        </div>
    </div>

    <x-charts.chart-card chart="attestation_by_state_chart__type" title="Répartition des statuts des attestations (par type)">
        <x-charts.pie-chart id="attestation_by_state_chart__type" url="attestation_by_state_chart" :colors="['#2289ce', '#abb8b8', '#749dc0']" />
    </x-charts.chart-card>
</div>
