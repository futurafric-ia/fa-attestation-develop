<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('users.index') }}
</x-slot>

<section>
    <x-section-header title="Utilisateurs">
        <x-slot name="actions">
            @can('user.create')
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="users.create">
                Nouveau
                <x-slot name="appendIcon">
                    <x-heroicon-o-plus class="w-4 h-4" />
                </x-slot>
            </x-button-link>
            @endcan
        </x-slot>
    </x-section-header>

    <livewire:user.users-table />
</section>
