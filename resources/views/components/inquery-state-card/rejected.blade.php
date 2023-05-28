@props(['count' => 0, 'textColor' => 'text-red-600', 'bgColor' => 'bg-red-600', 'url'])

<div class="items-center bg-white rounded-lg shadow">
    <div class="flex space-x-10 px-3 py-5">
        <div class="pl-3 {{ $textColor }} mt-3">
            <svg class="w-8 h-8" fill="currentColor" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <div>
            <p class="mb-2 text-sm font-medium text-blue-900">
                Rejetées
            </p>
            <p class="text-lg font-semibold text-gray-900">
                {{ $count }}
            </p>
        </div>
    </div>

    <div class="w-full {{ $bgColor }} rounded-b-lg p-2 text-sm text-center text-white font-semibold">
        <a href="{{ $url }}">
            Consulter
        </a>
    </div>
</div>
