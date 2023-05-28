<div class="md:flex justify-center">
    <a href="{{ route('request.show', $request->uuid) }}" class="mr-2">
        <x-heroicon-o-eye class="text-primary-700 h-4 w-4" id="details-{{$request->id}}" />
        <x-tooltip content="Consulter" placement="top" append-to="#details-{{$request->id}}" />
    </a>
    @can('request.create')
        @if ($request->state->label() == 'En attente')
            <a href="{{ route('request.edit', $request->uuid) }}" class="mr-2">
                <x-heroicon-o-pencil class="text-primary-700 dark:text-gray-200 h-4 w-4" id="edit-{{$request->id}}" />
                <x-tooltip content="Editer" placement="top" append-to="#edit-{{$request->id}}" />
            </a>
        @endif
    @endcan
</div>
