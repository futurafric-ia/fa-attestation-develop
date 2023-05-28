@props(['disabled' => false, 'name', 'id' => '', 'options', 'firstOption' => null, 'value' => null])

<select
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'form-select block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) !!}
>
    @if($firstOption)
        <option value="">{{ $firstOption }}</option>
    @endif

    @foreach($options as $option_value => $option_label)
        <option value="{{ $option_value }}" {{ $option_value == $value ? 'selected' : '' }}>
            {{ $option_label }}
        </option>
    @endforeach
</select>
