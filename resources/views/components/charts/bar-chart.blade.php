@props(['id', 'title' => '', 'url', 'height' => '400px', 'colors' => [], 'datasets' => ['bar'], 'params' => []])

<div id="{{ $id }}" style="height: {{ $height }}"></div>

@once
    @push('after-scripts')
        <script src="https://unpkg.com/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>
    @endpush
@endonce

@push('after-scripts')
<script>
    var queryParams = new URLSearchParams(@json($params));

    window[@json($id)] = new Chartisan({
        el: '#' + @json($id),
        url: '@chart($url)'+`?${queryParams}`,
        loader: {
            color: '#071c30',
            size: [30, 30],
            type: 'bar',
            textColor: '#000',
            text: 'Chargement des données...',
        },
        error: {
            color: '#071c30',
            size: [30, 30],
            text: 'Oh oh! Une erreur est survenue...',
            textColor: '#000',
            type: 'general',
        },
        hooks: new ChartisanHooks()
            .colors(@json($colors))
            .responsive()
            .beginAtZero()
            .legend({ position: 'bottom' })
            .datasets(@json($datasets)),
    });

    window.addEventListener('update-chart', function (event, data) {
        const defaultParams = @json($params);
        const queryParams = new URLSearchParams({ ...defaultParams, year: event.detail.year });

        window[event.detail.chart].update({
            url: '@chart($url)' + `?${queryParams}`
        })
    })
</script>
@endpush
