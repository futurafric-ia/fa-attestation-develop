<div class="w-full">
    <p class="block my-3 text-center">
        Veuillez renseigner les plages d'attestations ci-dessous:
    </p>
    <div>
        @foreach($ranges as $i => $range)
            <div class="flex items-center w-full space-x-4 mt-4">

                <div class="w-full">
                    <x-label for="ranges.{{$i}}.range_start" value="Début série" />
                    <x-input
                        required
                        type="number"
                        class="w-full"
                        name="ranges.{{$i}}.range_start"
                        id="ranges.{{$i}}.range_start"
                        wire:model.debounce.500ms="ranges.{{$i}}.range_start"
                    />
                    <x-input-error for="ranges.{{$i}}.range_start" class="mt-2" />
                </div>

                <div class="w-full">
                    <x-label for="ranges.{{$i}}.range_end" value="Fin série" />
                    <x-input
                        required
                        type="number"
                        class="w-full"
                        name="ranges.{{$i}}.range_end"
                        id="ranges.{{$i}}.range_end"
                        wire:model.debounce.500ms="ranges.{{$i}}.range_end"
                    />
                    <x-input-error for="ranges.{{$i}}.range_end" class="mt-2" />
                </div>

                <div>
                    <button type="button" wire:click.prevent="removeRange({{ $i }})" id="deleteAction-{{$i}}">
                        <x-heroicon-o-trash class="h-6 w-6 text-red-600" />
                    </button>
                    <x-tooltip content="Supprimer" placement="top" append-to="#deleteAction-{{$i}}" />
                </div>
            </div>
        @endforeach
    </div>

    @if ($this->canAddMoreRanges())
        <div wire:click.prevent="addRange" class="underline cursor-pointer text-gray-500 hover:text-gray-800 mt-4">
            + Ajouter une plage
        </div>
    @endif

    @if($showQuantity)
        <div class="my-4">
            <span class="font-bold">Quantité total: {{ $quantity }}</span>
        </div>
    @endif
</div>
