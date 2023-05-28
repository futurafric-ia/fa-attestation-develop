@props(['noLoadingIndicator' => false])

<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150']) }}>
    @isset($prependIcon)
        <span class="mr-1">{{ $prependIcon }}</span>
    @endisset
    @unless ($noLoadingIndicator)
        <div class="btn-spinner mr-2" style="border-top: 0.2em solid rgb(139, 136, 136); border-right:  rgb(139, 136, 136); border-bottom:  rgb(139, 136, 136);" wire:loading {{ $attributes->wire('target') }}></div>
    @endunless
    {{ $slot }}
    @isset($appendIcon)
        <span class="ml-1">{{ $appendIcon }}</span>
    @endisset
</button>
