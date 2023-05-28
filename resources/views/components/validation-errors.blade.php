@props(['field' => null, 'title' => 'Whoops! Une erreur est survenue.'])


<div>
@if ($field && $errors->has($field))
    <x-alert title="{{ $title }}" type="danger" {{ $attributes }}>
        <ul class="mt-3 list-disc list-inside text-sm leading-tight overflow-wrap break-words">
            @foreach ($errors->get($field) as $error)
                <li class="overflow-wrap">{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif

@if(! $field && $errors->any())
    <x-alert title="{{ $title }}" type="danger" {{ $attributes }}>
        <ul class="mt-3 list-disc list-inside text-sm leading-tight overflow-wrap">
            @foreach ($errors->all() as $error)
                <li class="overflow-wrap">{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif
</div>
