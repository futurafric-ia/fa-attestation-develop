<div>
    <div class="p-5 shadow bg-white rounded mb-5">
        <x-label value="Intermédiaire" class="my-2 text-base" />
        <livewire:broker.broker-select name="broker_id" placeholder="Choississez un intermediaire" :searchable="true" />
    </div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800">Vue d'ensemble</h2>
    </div>
    <div class="mt-2">
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
            <x-count-card
                title="Stock intermédiaire(s)"
                subtitle="{{ $totalAvailableStockByState[\Domain\Attestation\States\Attributed::$name] ?? 0 }}"
                bg-color="bg-blue-500"
                text-color="text-blue-500"
            >
                <x-slot name="icon">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="server w-8 h-8">
                        <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V5zm14 1a1
                         1 0 11-2 0 1 1 0 012 0zM2 13a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2zm14 1a1 1 0
                          11-2 0 1 1 0 012 0z" clip-rule="evenodd">
                        </path>
                    </svg>
                </x-slot>
            </x-count-card>

            <x-count-card
                title="Utilisées"
                subtitle="{{ $totalAvailableStockByState[\Domain\Attestation\States\Used::$name] ?? 0 }}"
                bg-color="bg-accent-900"
                text-color="text-accent-900"
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
            >
                <x-slot name="icon">
                    <svg class="w-8 h-8" fill="currentColor" stroke-linecap="round" stroke-width="2" stroke-linejoin="round">
                        <path fill="none" d="M0 0h24v24H0z"/><path d="M17 6h5v2h-2v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8H2V6h5V3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3zm1 2H6v12h12V8zm-4.586 6l1.768 1.768-1.414 1.414L12 15.414l-1.768 1.768-1.414-1.414L10.586 14l-1.768-1.768 1.414-1.414L12 12.586l1.768-1.768 1.414 1.414L13.414 14zM9 4v2h6V4H9z"/>
                    </svg>
                </x-slot>
            </x-count-card>
        </div>
    </div>
    <x-charts.chart-card chart="attestation_by_state_chart__broker" title="Répartition des statuts des attestations (par intermédiaire)">
        <x-charts.pie-chart id="attestation_by_state_chart__broker" url="attestation_by_state_chart" :colors="['#2289ce', '#abb8b8', '#749dc0']" />
    </x-charts.chart-card>
</div>
