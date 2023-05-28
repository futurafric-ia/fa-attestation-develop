<div class="md:flex">
    @if ($user->deleted_at !== null)
        <button wire:click="confirmUserRestoration({{$user->id}})" wire:loading.attr="disabled">
            <svg id="restore-{{$user->id}}" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 17l-2 2l2 2m-2-2h9a2 2 0 0 0 1.75-2.75l-.55-1"></path><path d="M8.536 11l-.732-2.732L5.072 9m2.732-.732l-5.5 7.794a2 2 0 0 0 1.506 2.89l1.141.024"></path><path d="M15.464 11l2.732.732L18.928 9m-.732 2.732l-5.5-7.794a2 2 0 0 0-3.256-.14l-.591.976"></path></g></svg>
        </button>
        <x-tooltip content="Restaurer" placement="top" append-to="#restore-{{$user->id}}" />
    @else
        <a href="{{ route('users.show', $user->uuid) }}" class="mr-2">
            <x-heroicon-o-eye class="text-gray-800 h-5 w-5" id="showAction-{{$user->id}}" />
            <x-tooltip content="Détails" placement="top" append-to="#showAction-{{$user->id}}" />
        </a>
        <a href="{{ route('users.edit', $user->uuid) }}" class="mr-2">
            <x-heroicon-o-pencil class="text-gray-800 dark:text-gray-200 h-5 w-5" id="editAction-{{$user->id}}" />
            <x-tooltip content="Editer" placement="top" append-to="#editAction-{{$user->id}}" />
        </a>
        @if ($user->id !== $logged_in_user->id && !$user->isSuperAdmin())
        <button wire:click="confirmUserDeletion({{$user->id}})" wire:loading.attr="disabled" class="mr-2">
            <x-heroicon-o-trash class="text-red-800 h-5 w-5" id="delete-{{$user->id}}" />
            <x-tooltip content="Supprimer" placement="top" append-to="#delete-{{$user->id}}" />
        </button>
        @endif
        @if($logged_in_user->isSuperAdmin())
        <button wire:click="impersonate({{$user->id}})" wire:loading.attr="disabled"  class="mr-2">
                <x-heroicon-o-login class="text-gray-800 h-5 w-5" id="impersonateAction-{{$user->id}}" />
                <x-tooltip content="Se connecter à ce compte" placement="top" append-to="#impersonateAction-{{$user->id}}" />
        </button>
        @endif
    @endif
</div>
