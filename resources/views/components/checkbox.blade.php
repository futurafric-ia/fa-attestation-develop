@props(['label'])

<div class="flex items-center">
    <input type="checkbox" {{ $attributes->merge(['class' => 'rounded border-gray-300 text-primary-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50']) }} />
    <span class="ml-2">{{ $label }}</span>
</div>
