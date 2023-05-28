<div class="flex space-x-2">
    <a href="{{ route('backend.roles.edit', $role) }}">
        <x-heroicon-o-pencil class="text-primary-700 h-5 w-5" id="edit-{{$role->id}}" />
        <x-tooltip content="Editer" placement="top" append-to="#edit-{{$role->id}}" />
    </a>

    @if ($role->users_count === 0)
    <button wire:click="confirmRoleDeletion({{ $role->id }})" wire:loading.attr="disabled">
        <x-heroicon-o-trash class="text-red-800 h-5 w-5" id="delete-{{ $role->id }}" />
    </button>
    <x-tooltip content="Supprimer" placement="top" append-to="#delete-{{$role->id}}" />
    @endif
</div>
