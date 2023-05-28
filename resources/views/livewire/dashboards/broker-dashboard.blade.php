@section('title', 'Tableau de bord')

@section('title', 'Tableau de bord')

<section>
    <x-dashboard-header>
        <x-slot name="actions">
            <a href="{{ route('request.create') }}" class="inline-flex items-center px-3 py-3 hover:text-white text-primary-700 hover:bg-primary-800 border border-blue-800 rounded-full font-semibold text-xs uppercase tracking-widest active:bg-primary-900 focus:outline-none focus:border-blue-900 focus:ring-gray transition ease-in-out duration-150">
                Faire une demande
                <span class="block ml-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="fill-current w-6 h-6">
                        <path fill="none" d="M0 0h24v24H0z"/>
                        <path d="M22 20.007a1 1 0 0 1-.992.993H2.992A.993.993 0 0 1 2 20.007V19h18V7.3l-8 7.2-10-9V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v16.007zM4.434 5L12 11.81 19.566 5H4.434zM0 15h8v2H0v-2zm0-5h5v2H0v-2z"/>
                    </svg>
                </span>
            </a>
        </x-slot>
    </x-dashboard-header>

    <div class="md:mx-auto sm:px-6 md:px-12 md:py-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Vue d'ensemble</h2>
        </div>
        <div class="mt-2">
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">

                <x-count-card
                    title="Utilisateurs"
                    subtitle="{{ $totalBrokerUsersCount }}"
                    bg-color="bg-blue-500"
                    text-color="text-blue-500"
                    link="{{ route('settings.show', ['#broker-member-manager']) }}"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                            </path>
                        </svg>
                    </x-slot>
                </x-count-card>

                <div class="flex-none items-center bg-white rounded-lg shadow">
                    <div class="flex space-x-10 px-3 py-5">
                        <div class="pl-3 text-orange-800 mt-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill="none" d="M0 0H24V24H0z"/>
                                <path
                                    d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2zm0 2c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8zm0 1c1.018 0 1.985.217 2.858.608L13.295 7.17C12.882 7.06 12.448 7 12 7c-2.761 0-5 2.239-5 5 0 1.38.56 2.63 1.464 3.536L7.05 16.95l-.156-.161C5.72 15.537 5 13.852 5 12c0-3.866 3.134-7 7-7zm6.392 4.143c.39.872.608 1.84.608 2.857 0 1.933-.784 3.683-2.05 4.95l-1.414-1.414C16.44 14.63 17 13.38 17 12c0-.448-.059-.882-.17-1.295l1.562-1.562zm-2.15-2.8l1.415 1.414-3.724 3.726c.044.165.067.338.067.517 0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2c.179 0 .352.023.517.067l3.726-3.724z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium opacity-75">
                                En attente de traitement
                            </p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $totalRequestCountByState[\Domain\Request\States\Pending::$name] ?? 0 }}
                            </p>
                        </div>
                    </div>
                    <div class="w-full bg-orange-800 rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                        <a href="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Pending::$name]]) }}"> Consulter </a>
                    </div>
                </div>

                <div class="flex-none items-center bg-white rounded-lg shadow">
                    <div class="flex space-x-10 px-3 py-5">
                        <div class="pl-3 text-orange-900 mt-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path fill="none" d="M0 0h24v24H0z"/><path d="M6.382 5.968A8.962 8.962 0 0 1 12 4c2.125 0 4.078.736 5.618 1.968l1.453-1.453 1.414 1.414-1.453 1.453a9 9 0 1 1-14.064 0L3.515 5.93l1.414-1.414 1.453 1.453zM12 20a7 7 0 1 0 0-14 7 7 0 0 0 0 14zm1-8h3l-5 6.5V14H8l5-6.505V12zM8 1h8v2H8V1z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-gray-900">
                                En cours de traitement
                            </p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $totalRequestCountByState[\Domain\Request\States\Approved::$name] ?? 0 }}
                            </p>
                        </div>
                    </div>
                    <div class="w-full bg-orange-900 rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                        <a href="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Approved::$name]]) }}"> Consulter </a>
                    </div>
                </div>
            </div>
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                <div class="items-center bg-white rounded-lg shadow">
                    <div class="flex space-x-10 px-3 py-5">
                        <div class="pl-3 text-accent-700 mt-3">
                            <svg class="w-8 h-8" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path fill-rule="evenodd" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-blue-900">
                                Validées
                            </p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $totalRequestCountByState[\Domain\Request\States\Validated::$name] ?? 0 }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full bg-accent-700 rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                        <a href="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Validated::$name]]) }}"> Consulter </a>
                    </div>
                </div>

                <div class="items-center bg-white rounded-lg shadow">
                    <div class="flex space-x-10 px-3 py-5">
                        <div class="pl-3 text-accent-900 mt-3">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                <path fill="none" d="M0 0h24v24H0z"/><path d="M8.965 18a3.5 3.5 0 0 1-6.93 0H1V6a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2h3l3 4.056V18h-2.035a3.5 3.5 0 0 1-6.93 0h-5.07zM15 7H3v8.05a3.5 3.5 0 0 1 5.663.95h5.674c.168-.353.393-.674.663-.95V7zm2 6h4v-.285L18.992 10H17v3zm.5 6a1.5 1.5 0 1 0 0-3.001 1.5 1.5 0 0 0 0 3.001zM7 17.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-blue-900">
                                Livrées
                            </p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $totalRequestCountByState[\Domain\Request\States\Delivered::$name] ?? 0 }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full bg-accent-900 rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                        <a href="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Delivered::$name]]) }}"> Consulter </a>
                    </div>
                </div>
                <x-dynamic-component
                    component="inquery-state-card.{{ \Domain\Request\States\Rejected::$name }}"
                    :count="$totalRequestCountByState[\Domain\Request\States\Rejected::$name] ?? 0"
                    url="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Rejected::$name]]) }}"
                />
                <div class="items-center bg-white rounded-lg shadow">
                    <div class="flex space-x-10 px-3 py-5">
                        <div class="pl-3 text-secondary-900 mt-3">
                            <svg class="w-8 h-8" fill="currentColor" stroke-linecap="round" stroke-width="2" stroke-linejoin="round">
                                <path fill="none" d="M0 0h24v24H0z"/><path d="M17 6h5v2h-2v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8H2V6h5V3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3zm1 2H6v12h12V8zm-4.586 6l1.768 1.768-1.414 1.414L12 15.414l-1.768 1.768-1.414-1.414L10.586 14l-1.768-1.768 1.414-1.414L12 12.586l1.768-1.768 1.414 1.414L13.414 14zM9 4v2h6V4H9z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="mb-2 text-sm font-medium text-blue-900">
                                Annulées
                            </p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $totalRequestCountByState[\Domain\Request\States\Cancelled::$name] ?? 0 }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full bg-secondary-900 rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                        <a href="{{ route('request.index', ['filters' => ['status' => \Domain\Request\States\Cancelled::$name]]) }}"> Consulter </a>
                    </div>
                </div>
            </div>
        </div>
        <x-charts.chart-card chart="delivery_by_type_and_month_chart" title="Livraisons par type et par mois">
            <x-charts.bar-chart id="delivery_by_type_and_month_chart" url="delivery_by_type_and_month_chart" :colors="['#2289ce', '#abb8b8', '#831843']" />
        </x-charts.chart-card>
    </div>
</section>
