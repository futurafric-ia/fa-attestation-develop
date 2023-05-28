<div class="md:flex">
    <a href="{{ route('suppliers.show', $supplier->id) }}" class="mr-2" title="Détails">
        <x-heroicon-o-eye class="text-primary-700 dark:text-gray-200 h-5 w-5" id="details-{{$supplier->id}}" />
        <x-tooltip content="Détails" placement="top" append-to="#details-{{$supplier->id}}" />
    </a>
    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="mr-2" title="modifier">
        <x-heroicon-o-pencil class="text-primary-700 dark:text-gray-200 h-5 w-5" id="edit-{{$supplier->id}}" />
        <x-tooltip content="Editer" placement="top" append-to="#edit-{{$supplier->id}}" />
    </a>
    @if ($supplier->supplies_count === 0)
    <button wire:click="confirmSupplierDeletion({{$supplier->id}})" wire:loading.attr="disabled" title="supprimer">
        <x-heroicon-o-trash class="text-red-800 h-5 w-5" id="delete-{{$supplier->id}}" />
    </button>
    <x-tooltip content="Supprimer" placement="top" append-to="#delete-{{$supplier->id}}" />
    @endif
</div>
