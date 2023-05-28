@section("title", "Tableau de bord")
<div>
    <x-dashboard-header></x-dashboard-header>

    <div class=" md:mx-auto sm:px-6 md:px-12 md:py-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Vue d'ensemble</h2>
        </div>
        <div class="mt-2">
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-2">
                <x-count-card
                    title="Utilisateurs"
                    subtitle="{{ $totalUserCount }}"
                    bg-color="bg-blue-600"
                    text-color="text-blue-600"
                    link="{{ route('users.index') }}"
                >
                    <x-slot name="icon">
                        <x-heroicon-o-user-group class="w-8 h-8"/>
                    </x-slot>
                </x-count-card>

                <x-count-card
                    title="Intermédiaires"
                    subtitle="{{ $totalBrokerCount }}"
                    bg-color="bg-accent-900"
                    text-color="text-accent-900"
                    link="{{ route('brokers.index') }}"
                >
                    <x-slot name="icon">
                        <x-heroicon-o-office-building class="w-8 h-8"/>
                    </x-slot>
                </x-count-card>
            </div>
        </div>

        <x-charts.chart-card chart="delivery_request_ratio_chart" title="Ratio demandes et livraisons">
            <x-charts.bar-chart id="delivery_request_ratio_chart" url="delivery_request_ratio_chart" :colors="['#2289ce', '#abb8b8', '#749dc0']" :datasets="[['type' => 'line', 'fill' => true], 'bar']" />
        </x-charts.chart-card>
    </div>
</div>
