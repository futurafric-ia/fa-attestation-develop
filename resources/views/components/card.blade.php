@props(['title' => null, 'subtitle' => null, 'titleClass' => '', 'subtitleClass' => ''])

<div {{ $attributes->merge(['class' => "bg-white shadow overflow-hidden block rounded-lg"]) }}>
    @if ($title)
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 {{ $titleClass }}">
            {{ $title }}
        </h3>
        @if($subtitle)
        <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-600 {{ $subtitleClass }}">
            {{ $subtitle }}
        </p>
        @endif
    </div>
    @endif
    <div>
        {{ $slot }}
    </div>
</div>
