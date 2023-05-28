<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('request.index') }}
</x-slot>

<section>
    <x-section-header title="Demandes">
        <x-slot name="actions">
            @can('request.create')
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="request.create">
                    Nouveau
                <x-slot name="appendIcon">
                    <x-heroicon-o-plus class="w-4 h-4" />
                </x-slot>
            </x-button-link>
            @endcan
        </x-slot>
    </x-section-header>

    <livewire:request.requests-table />
</section>
