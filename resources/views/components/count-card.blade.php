@props(['title', 'subtitle', 'link' => null, 'linkTitle' => null, 'bgColor' => 'bg-gray-300', 'textColor' => 'text-white'])

<div class="flex flex-col justify-between bg-gray-50 rounded-lg shadow-md">
    <div class="flex space-x-10 px-3 py-5">
        <div class="pl-3 {{ $textColor }} mt-3">
            @isset($icon)
                {{ $icon }}
            @else
                <svg class="w-8 h-8" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path fill-rule="evenodd" d="M5 13l4 4L19 7"></path>
                </svg>
            @endisset
        </div>
        <div class="">
            <p class="mb-2 text-sm font-medium opacity-75">
                {{ $title }}
            </p>
            <p class="text-lg font-semibold text-gray-900">
                {{ $subtitle }}
            </p>
        </div>
    </div>
    @isset($action)
        {{ $action }}
    @else
        @if ($link)
            <div class="w-full {{ $bgColor }} rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                <a href="{{ $link }}"> {{ $linkTitle ? $linkTitle : 'Consulter' }} </a>
            </div>
        @endif
    @endisset
</div>
