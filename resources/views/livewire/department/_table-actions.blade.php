<div class="flex space-x-2">
    <a href="{{ route('backend.departments.edit', $department) }}">
        <x-heroicon-o-pencil class="text-primary-700 h-5 w-5" id="edit-{{ $department->id }}" />
        <x-tooltip content="Editer" placement="top" append-to="#edit-{{$department->id}}" />
    </a>
    @if (! $department->hasUsers())
    <button wire:click="confirmDepartmentDeletion({{ $department->id }})" wire:loading.attr="disabled" class="mr-2">
        <x-heroicon-o-trash class="text-red-800 h-5 w-5" id="delete-{{ $department->id }}" />
        <x-tooltip content="Supprimer" placement="top" append-to="#delete-{{$department->id}}" />
    </button>
    @endif
</div>
