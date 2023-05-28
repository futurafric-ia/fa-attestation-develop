<div class="flex space-x-2">
    <button type="button" id="show-{{$delivery->id}}" @click="showingDeliveryAttestations = true; $wire.call('showDeliveryAttestations', {{ $delivery->id }})">
        <x-heroicon-o-collection class="text-primary-700 dark:text-gray-200 h-4 w-4"/>
    </button>
    <x-tooltip content="Voir les attestations" placement="top" append-to="#show-{{$delivery->id}}" />
</div>
