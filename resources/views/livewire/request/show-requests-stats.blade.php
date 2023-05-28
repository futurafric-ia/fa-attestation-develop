<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('request.stats') }}
</x-slot>

<section>
    <x-section-header title="Statistiques des demandes et livraisons" />

    <div class="mt-4">
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-2">

            <x-count-card
                title="Demandes livrées"
                subtitle="{{ $totalRequestDeliveredCount }}"
                bg-color="bg-green-400"
                text-color="text-green-400"
                link="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Delivered::$name]]) }}"
            >
                <x-slot name="icon">
                    <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="none" d="M0 0h24v24H0z"/>
                        <path
                            d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.001a.996.996 0 0 1-.993.999H3.993A.996.996 0 0 1 3 20.001V10zm16 0H5v9h14v-9zM4 5v3h16V5H4zm5 7h6v2H9v-2z"/>
                    </svg>
                </x-slot>
            </x-count-card>

            <x-count-card
                title="Demandes à livrer"
                subtitle="{{ $totalRequestValidatedCount }}"
                bg-color="bg-yellow-300"
                text-color="text-yellow-300"
                link="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Validated::$name]]) }}"
            >
                <x-slot name="icon">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                        </path>
                    </svg>
                </x-slot>
            </x-count-card>
        </div>

        <x-charts.chart-card chart="delivery_request_ratio_chart" title="Ratio demandes et livraisons">
            <x-charts.bar-chart id="delivery_request_ratio_chart" url="delivery_request_ratio_chart" :colors="['#2289ce', '#abb8b8', '#749dc0']" :datasets="[['type' => 'line', 'fill' => true], 'bar']" />
        </x-charts.chart-card>
    </div>
</section>
