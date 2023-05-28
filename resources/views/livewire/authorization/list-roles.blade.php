<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('backend.roles.index') }}
</x-slot>

<section>
    <x-section-header title="Rôles et permissions">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="backend.roles.create">
                Nouveau
                <x-slot name="appendIcon">
                    <x-heroicon-o-plus class="w-4 h-4"></x-heroicon-o-plus>
                </x-slot>
            </x-button-link>
        </x-slot>
    </x-section-header>

    <livewire:authorization.roles-table />
</section>
