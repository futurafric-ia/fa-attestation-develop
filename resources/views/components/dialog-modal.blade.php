@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        @isset($title)
            <div class="text-lg font-semibold">
                {{ $title }}
            </div>
        @endisset

        <div class="mt-4">
            {{ $content }}
        </div>
    </div>

    @isset($footer)
        <div class="px-6 py-4 bg-gray-100 text-right flex justify-end">
            {{ $footer }}
        </div>
    @endif
</x-modal>
