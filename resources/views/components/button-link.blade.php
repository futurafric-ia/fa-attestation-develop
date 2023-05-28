@props(['route' => null, 'href' => null])

<a href="{{ $route ? route($route) : ($href ?? '#') }}" {{ $attributes->merge(['class' => 'btn btn-gray']) }}>
    @isset($prependIcon)
        <span class="mr-1">{{ $prependIcon }}</span>
    @endisset
    {{ $slot }}
    @isset($appendIcon)
        <span class="ml-1">{{ $appendIcon }}</span>
    @endisset
</a>
