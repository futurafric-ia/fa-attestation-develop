<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('brokers.index') }}
</x-slot>

<section>
    <x-section-header title="Intermédiaires">
        <x-slot name="actions">
            @can('broker.create')
            <x-button-link class="btn-secondary" route="brokers.create">
                Nouveau
                <x-slot name="appendIcon">
                    <x-heroicon-o-plus class="w-4 h-4" />
                </x-slot>
            </x-button-link>
            @endcan
        </x-slot>
    </x-section-header>

    <livewire:broker.brokers-table />
</section>
