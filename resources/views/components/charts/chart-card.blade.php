@props(['title' => '', 'chart'])

<div class="border rounded-lg shadow-sm xl:col-span-3 bg-white">
    <!-- Card header -->
    <div class="flex items-center justify-between px-4 py-2 border-b">
      <h5 class="font-semibold">{{ $title }}</h5>
      <div class="flex items-center">
        <span class="block mr-2 text-sm text-gray-600">Année d'exercice:</span>
        <select
            x-on:change="$dispatch('update-chart', { chart: '{{ $chart }}', year: $event.target.value })"
            class="form-select relative py-2 pl-3 pr-10 transition duration-150 ease-in-out rounded-md sm:text-sm sm:leading-5"
        >
            @foreach (range(\Carbon\Carbon::now()->year, 2020) as $year)
            <option value="{{ $year }}">
                {{ $year }}
            </option>
            @endforeach
        </select>
      </div>
    </div>
    <!-- Chart -->
    <div class="relative min-w-0 p-4">
        {{ $slot }}
    </div>
</div>
