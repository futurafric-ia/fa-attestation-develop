<div class="md:flex justify-center space-x-2">
    @if ($broker->deleted_at !== null)
    <button wire:click="confirmBrokerRestoration({{$broker->id}})" wire:loading.attr="disabled">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="text-green-600 h-5 w-5" id="restore-{{$broker->id}}"><path fill="none" d="M0 0h24v24H0z"/><path d="M5.828 7l2.536 2.536L6.95 10.95 2 6l4.95-5.95 1.414 1.414L5.828 5H13a8 8 0 1 1 0 16H4v-2h9a6 6 0 1 0 0-12H5.828z"/></svg>
    </button>
    <x-tooltip content="Restaurer" placement="top" append-to="#restore-{{$broker->id}}" />
    @else
    <a href="{{ route('brokers.show', $broker) }}">
        <x-heroicon-o-eye class="text-primary-700 h-5 w-5" id="details-{{$broker->id}}" />
        <x-tooltip content="Détails" placement="top" append-to="#details-{{$broker->id}}" />
    </a>
    <a href="{{ route('brokers.edit', $broker) }}">
        <x-heroicon-o-pencil class="text-primary-700 h-5 w-5" id="edit-{{$broker->id}}" />
        <x-tooltip content="Editer" placement="top" append-to="#edit-{{$broker->id}}" />
    </a>
    <button wire:click="confirmBrokerDeletion({{$broker->id}})" wire:loading.attr="disabled">
        <x-heroicon-o-trash class="text-red-800 h-5 w-5" id="delete-{{$broker->id}}" />
        <x-tooltip content="Supprimer" placement="top" append-to="#delete-{{$broker->id}}" />
    </button>
    @endif
</div>
