@section('title', 'Tableau de bord')

<section>
    <x-dashboard-header></x-dashboard-header>

    <div class=" md:mx-auto sm:px-6 md:px-12 md:py-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Vue d'ensemble</h2>
        </div>
        <div class="mt-2">
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
                <x-count-card
                    title="Utilisateurs"
                    subtitle="{{ $totalUserCount }}"
                    bg-color="bg-blue-700"
                    text-color="text-blue-700"
                    link="{{ route('users.index') }}"
                >
                    <x-slot name="icon">
                        <x-heroicon-o-user-group class="w-8 h-8"/>
                    </x-slot>
                </x-count-card>

                <x-count-card
                    title="Intermédiaires"
                    subtitle="{{ $totalBrokerCount }}"
                    bg-color="bg-secondary-900"
                    text-color="text-secondary-900"
                    link="{{ route('brokers.index') }}"
                >
                    <x-slot name="icon">
                        <x-heroicon-o-office-building class="w-8 h-8"/>
                    </x-slot>
                </x-count-card>

                <x-count-card
                    title="Fournisseurs"
                    subtitle="{{ $totalSupplierCount }}"
                    bg-color="bg-accent-900"
                    text-color="text-accent-900"
                    link="{{ route('suppliers.index') }}"
                >
                    <x-slot name="icon">
                        <x-heroicon-o-truck class="w-8 h-8" />
                    </x-slot>
                </x-count-card>
            </div>
        </div>

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Activités récentes</h2>
        </div>

        <x-table
            :columns="[
                'Opération' => 'log_name',
                'Description' => 'description',
                'Date' => fn($item) => $item->created_at,
            ]"
            :rows="$latestActivities"
        />

    </div>
</section>
