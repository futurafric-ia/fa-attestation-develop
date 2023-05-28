<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('supply.index') }}
</x-slot>

<section>
    <x-section-header title="Approvisionnements">
        <x-slot name="actions">
            @can('supply.create')
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="supply.supplyStockForm">
                <x-slot name="appendIcon">
                    <x-heroicon-o-plus class="w-4 h-4" />
                </x-slot>
                Nouveau
            </x-button-link>
            @endcan
        </x-slot>
    </x-section-header>

    <livewire:supply.supplies-table />
</section>
