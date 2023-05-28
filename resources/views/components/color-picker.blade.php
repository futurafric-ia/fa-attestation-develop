@props(['color' => '#ffffff'])

@once
    @push('after-scripts')
    <script src="https://unpkg.com/vanilla-picker@2"></script>
    @endpush
@endonce

<div
    x-data="{ color: '{{ $color }}' }"
    x-init="
        picker = new Picker($refs.button);
        picker.onDone = rawColor => {
            color = rawColor.hex;
            $dispatch('input', color)
        }
    "
    wire:ignore
    {{ $attributes->merge(['class' => 'px-6 py-2']) }}
>
    <span x-text="color" :style="`background: ${color}`" class="border p-2 border-gray-200 rounded mr-2"></span>
    <button x-ref="button">Changer</button>
</div>
