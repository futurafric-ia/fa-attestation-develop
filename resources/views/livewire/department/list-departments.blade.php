<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('backend.departments.index') }}
</x-slot>

<section>
    <x-section-header title="Départements">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="backend.departments.create">
                Nouveau
                <x-slot name="appendIcon">
                    <x-heroicon-o-plus class="w-4 h-4"></x-heroicon-o-plus>
                </x-slot>
            </x-button-link>
        </x-slot>
    </x-section-header>

    <livewire:department.departments-table />
</section>
