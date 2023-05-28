@props(['rows' => 5, ])

<textarea
    rows="{{ $rows }}"
    {{ $attributes->merge(['class' => 'form-textarea block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50']) }}
>
    {{ $slot }}
</textarea>
