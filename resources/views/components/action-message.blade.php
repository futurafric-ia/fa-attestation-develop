@props(['on' => null, 'timeout' => 2000])

@if ($on)
    <div
        x-data="{ shown: false, timeout: null }"
        x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, '{{ $timeout}}');  })"
        x-show.transition.opacity.out.duration.1500ms="shown"
        style="display: none;"
        {{ $attributes->merge(['class' => 'text-sm text-gray-600']) }}
    >
        {{ $slot ?? 'Enregistré.' }}
    </div>
@else
    <div
        x-data="{ shown: false, timeout: null }"
        x-init="function handler() { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, '{{ $timeout}}');  }"
        x-show.transition.opacity.out.duration.1500ms="shown"
        style="display: none;"
        {{ $attributes->merge(['class' => 'text-sm text-gray-600']) }}
    >
        {{ $slot ?? 'Enregistré.' }}
    </div>
@endif
