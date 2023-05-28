<div class="md:flex justify-center">
    <button type="button" id="details-{{$attestation->id}}" class="text-white cursor-pointer" x-on:click="displayingSlideover = true; $wire.displaySlideover({{$attestation->id}})">
        <x-heroicon-o-eye class="text-primary-700 h-4 w-4" />
    </button>
    <x-tooltip content="Détails" placement="top" append-to="#details-{{$attestation->id}}" />
</div>
