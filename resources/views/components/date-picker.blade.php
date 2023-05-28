@props(['mode' => 'single', 'dateFormat' => 'd-m-Y'])

<div class="relative flatpickr">
    <input
        x-data
        x-ref="date_input"
        x-init="flatpickr($refs.date_input, {
            dateFormat: '{{ $dateFormat }}',
            mode: '{{ $mode }}',
            locale: 'fr',
            onChange(selectedDates, dateStr) {
                console.log(selectedDates, dateStr)
            }
        });"
        type="text"
        {{ $attributes->merge(['class' => 'focus:ring text-gray-800 shadow-sm focus:outline-none leading-none form-input disabled:opacity-50']) }}
        data-input
    >
    <div class="absolute top-0 right-0 px-3 py-2" data-toggle>
        <svg class="h-6 w-6 text-gray-400"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
</div>

@once
    @push('after-styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('after-scripts')
        <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
        <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
    @endpush
@endonce
