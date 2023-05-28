<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('suppliers.index') }}
</x-slot>

<section>
    <x-section-header title="Fournisseurs">
        <x-slot name="actions">
            @can('supplier.create')
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="suppliers.create">
                Nouveau
                <x-slot name="appendIcon">
                    <x-heroicon-o-plus class="w-4 h-4" />
                </x-slot>
            </x-button-link>
            @endcan
        </x-slot>
    </x-section-header>

    <livewire:supplier.suppliers-table />
</section>
