<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('delivery.showInvoice') }}
</x-slot>

<section>

    <x-section-header title="Borderaux de livraisons">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="delivery.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
            @if ($selectedDelivery)
            <x-button-link class="btn-white" target="_blank" href="{{ route('delivery.showInvoicePdf', $selectedDelivery->id, ['download' => true]) }}">
                Télécharger le PDF
                <x-slot name="appendIcon">
                    <x-heroicon-o-download class="w-4 h-4"></x-heroicon-o-download>
                </x-slot>
            </x-button-link>
            @endif
        </x-slot>
    </x-section-header>

    <div class="grid gap-8 md:grid-cols-12">
        <div class="col-span-4 md:col-span-4 bg-white shadow rounded max-h-full">
            <div class="px-2 py-3 flex items-center space-x-2">
                <div class="w-full">
                    <x-input placeholder="Entrez le code de livraison ou l'intermédiaire" id="search" class="w-full" wire:model.defer="searchTerm"></x-input>
                </div>
                <div>
                    <x-button class="btn-secondary" wire:click="search" wire:target="search">
                        <div class="btn-spinner" wire:loading wire:target="search"></div>
                        <x-heroicon-o-search class="w-5 h-5" wire:target="search" wire:loading.remove></x-heroicon-o-search>
                    </x-button>
                </div>
            </div>
            <div class="overflow-y">
                @forelse($deliveries as $delivery)
                    <div
                        class="flex flex-col border-b cursor-pointer hover:bg-primary-100 {{ $selectedDelivery && $delivery->id === $selectedDelivery->id ? 'bg-primary-100' : '' }}"
                        wire:key="{{ $delivery->id }}"
                        wire:click="setSelectedInvoice({{$delivery->id}})"
                    >
                        <div class="p-2 flex flex-col">
                            <div class="mb-2">{{ $delivery->broker->name }} ({{ $delivery->broker->code }})</div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="block">
                                    {{ $delivery->code }}
                                </span>
                                <span class="block">
                                    {{ $delivery->delivered_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Aucun résultats trouvés</p>
                @endforelse
            </div>
        </div>
        <div class="col-span-8 relative h-screen border rounded">
            @if ($selectedDelivery)
            <div
                class="absolute inset-0 bg-gray opacity-50 h-full flex flex-col items-center justify-center"
                id="iframe-overlay"
            >
                <div class="text-white font-semibold flex flex-col items-center justify-center">
                    <div>
                        <x-heroicon-o-refresh class="h-10 w-10 animate-spin" />
                    </div>
                    <div>
                        Chargement du document en cours
                    </div>
                </div>
            </div>
            <iframe id="delivery-iframe" class="w-full min-h-full" src="{{ route('delivery.showInvoicePdf', $selectedDelivery->id) }}"></iframe>
            @endif
        </div>
    </div>

</section>

@push('after-scripts')
    <script>
        $invoiceIframe = document.querySelector('#delivery-iframe');

        if ($invoiceIframe) {
            $invoiceIframe.addEventListener('load', function() {
                $overlay = document.querySelector('#iframe-overlay')

                if ($overlay) {
                    $overlay.style.display = 'none'
                }
            })
        }
    </script>
@endpush
