<div class="flex space-x-2">
    <a href="{{ route('backend.imprimes.edit', $attestationType) }}">
        <x-heroicon-o-pencil class="text-primary-700 h-5 w-5" id="edit-{{ $attestationType->id }}" />
        <x-tooltip content="Editer" placement="top" append-to="#edit-{{$attestationType->id}}" />
    </a>

    @if ($attestationType->supplies_count === 0 && $attestationType->scans_count === 0 && $attestationType->requests_count === 0)
    <button wire:click="confirmAttestationTypeDeletion({{ $attestationType->id }})" wire:loading.attr="disabled">
        <x-heroicon-o-trash class="text-red-800 h-5 w-5" id="delete-{{ $attestationType->id }}" />
    </button>
    <x-tooltip content="Supprimer" placement="top" append-to="#delete-{{$attestationType->id}}" />
    @endif
</div>
