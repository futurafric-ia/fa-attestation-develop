<div class="md:flex">
    @if ($scan->isDone())
        <a href="{{ route('scan.show', $scan) }}" class="mr-2">
            <x-heroicon-o-eye class="text-primary-700 h-4 w-4" id="showAction" />
             <x-tooltip content="Détails" placement="top" append-to="#showAction" />
        </a>
        <a href="{{ route('scan.show.attestations.index', $scan) }}">
            <x-heroicon-o-collection class="text-primary-700 h-4 w-4" id="list" />
             <x-tooltip content="Consulter" placement="top" append-to="#list" />
        </a>
    @endif
</div>
