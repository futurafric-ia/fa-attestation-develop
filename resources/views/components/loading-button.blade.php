<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-gray flex items-center']) }}>
    @isset($prependIcon)
        <span class="mr-1">{{ $prependIcon }}</span>
    @endisset
    <div class="btn-spinner mr-2" wire:loading {{ $attributes->wire('target') }}></div>
    {{ $slot }}
    @isset($appendIcon)
        <span class="ml-1">{{ $appendIcon }}</span>
    @endisset
</button>
