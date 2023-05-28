@section('title', 'Tableau de bord')

<section>
    <x-dashboard-header></x-dashboard-header>

    <div class="md:mx-auto sm:px-6 md:px-12 md:py-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Vue d'ensemble</h2>
        </div>
        <div class="mt-2">
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                <x-count-card
                    title="En attente"
                    subtitle="{{ $totalRequestCountByState[\Domain\Request\States\Pending::$name] ?? 0 }}"
                    bg-color="bg-blue-600"
                    text-color="text-blue-600"
                    link="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Pending::$name]]) }}"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill="none" d="M0 0H24V24H0z"/>
                            <path
                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2zm0 2c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8zm0 1c1.018 0 1.985.217 2.858.608L13.295 7.17C12.882 7.06 12.448 7 12 7c-2.761 0-5 2.239-5 5 0 1.38.56 2.63 1.464 3.536L7.05 16.95l-.156-.161C5.72 15.537 5 13.852 5 12c0-3.866 3.134-7 7-7zm6.392 4.143c.39.872.608 1.84.608 2.857 0 1.933-.784 3.683-2.05 4.95l-1.414-1.414C16.44 14.63 17 13.38 17 12c0-.448-.059-.882-.17-1.295l1.562-1.562zm-2.15-2.8l1.415 1.414-3.724 3.726c.044.165.067.338.067.517 0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2c.179 0 .352.023.517.067l3.726-3.724z"/>
                        </svg>
                    </x-slot>
                </x-count-card>

                <x-count-card
                    title="Approuvées"
                    subtitle="{{ $totalRequestCountByState[\Domain\Request\States\Approved::$name] ?? 0 }}"
                    bg-color="bg-accent-900"
                    text-color="text-accent-900"
                    link="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Approved::$name]]) }}"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path fill-rule="evenodd" d="M5 13l4 4L19 7"></path>
                        </svg>

                    </x-slot>
                </x-count-card>

                <x-count-card
                    title="Rejetées"
                    subtitle="{{ $totalRequestCountByState[\Domain\Request\States\Rejected::$name] ?? 0 }}"
                    bg-color="bg-secondary-600"
                    text-color="text-secondary-600"
                    link="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Rejected::$name]]) }}"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8" fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                             stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </x-slot>
                </x-count-card>

                <x-count-card
                    title="Annulées"
                    subtitle="{{ $totalRequestCountByState[\Domain\Request\States\Cancelled::$name] ?? 0 }}"
                    bg-color="bg-secondary-900"
                    text-color="text-secondary-900"
                    link="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Cancelled::$name]]) }}"

                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8" fill="currentColor" stroke-linecap="round" stroke-width="2" stroke-linejoin="round">
                            <path fill="none" d="M0 0h24v24H0z"/><path d="M17 6h5v2h-2v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8H2V6h5V3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3zm1 2H6v12h12V8zm-4.586 6l1.768 1.768-1.414 1.414L12 15.414l-1.768 1.768-1.414-1.414L10.586 14l-1.768-1.768 1.414-1.414L12 12.586l1.768-1.768 1.414 1.414L13.414 14zM9 4v2h6V4H9z"/>
                        </svg>
                    </x-slot>
                </x-count-card>
            </div>
        </div>
        <x-charts.chart-card chart="request_approved_by_type_and_month_chart" title="Demandes approuvées par type et par mois">
            <x-charts.bar-chart id="request_approved_by_type_and_month_chart" url="request_approved_by_type_and_month_chart" :colors="['#2289ce', '#abb8b8', '#749dc0']" />
        </x-charts.chart-card>
    </div>
</section>
