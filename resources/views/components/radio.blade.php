@props(['label'])

<div class="inline-flex items-center">
    <input type="radio" {{ $attributes->merge(['class' => 'form-radio']) }}>
    <span class="ml-2">{{ $label }}</span>
</div>
