<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-gray']) }}>
    @isset($prependIcon)
        <span class="mr-1">{{ $prependIcon }}</span>
    @endisset
    {{ $slot }}
    @isset($appendIcon)
        <span class="ml-1">{{ $appendIcon }}</span>
    @endisset
</button>
