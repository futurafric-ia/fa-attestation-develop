<div class="w-full">
    @foreach($items as $i => $item)
        <fieldset class="mb-2 border border-gray-200 relative">
            <legend class="text-base text-gray-900 mt-4 mb-2 font-semibold">
                Détails de l'attestation
            </legend>
            <button wire:click.prevent="removeItem({{ $i }})" class="p-1 bg-red-500 text-xs text-gray-50 rounded absolute top-0 right-0">
                <x-heroicon-o-trash class="h-5 w-5" />
            </button>
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="items.{{$i}}.attestation_number" value="Numéro de l'attestation" />
                        <x-input
                            name="items.{{$i}}.attestation_number"
                            type="number"
                            class="w-full"
                            id="items.{{$i}}.attestation_number"
                            wire:model.lazy="items.{{$i}}.attestation_number"
                        />
                        <x-input-error for="items.{{$i}}.attestation_number" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="items.{{$i}}.insured_name" value="Nom de l'assuré" />
                        <x-input
                            name="items.{{$i}}.insured_name"
                            class="w-full"
                            id="items.{{$i}}.insured_name"
                            wire:model.lazy="items.{{$i}}.insured_name"
                        />
                        <x-input-error for="items.{{$i}}.insured_name" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="items.{{$i}}.matriculation" value="Immatriculation" />
                        <x-input
                            name="items.{{$i}}.matriculation"
                            class="w-full"
                            id="items.{{$i}}.matriculation"
                            wire:model.lazy="items.{{$i}}.matriculation"
                        />
                        <x-input-error for="items.{{$i}}.matriculation" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="items.{{$i}}.police_number" value="Numéro de police" />
                        <x-input
                            name="items.{{$i}}.police_number"
                            class="w-full"
                            id="items.{{$i}}.police_number"
                            wire:model.lazy="items.{{$i}}.police_number"
                        />
                        <x-input-error for="items.{{$i}}.police_number" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="items.{{$i}}.address" value="Adresse" />
                        <x-input
                            name="items.{{$i}}.address"
                            class="w-full"
                            id="items.{{$i}}.address"
                            wire:model.lazy="items.{{$i}}.address"
                        />
                        <x-input-error for="items.{{$i}}.address" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="items.{{$i}}.make" value="Marque et type" />
                        <x-input
                            name="items.{{$i}}.make"
                            class="w-full"
                            id="items.{{$i}}.make"
                            wire:model.lazy="items.{{$i}}.make"
                        />
                        <x-input-error for="items.{{$i}}.make" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="items.{{$i}}.start_date" value="Date de début" />
                        <x-input
                            name="items.{{$i}}.start_date"
                            type="date"
                            class="w-full"
                            id="items.{{$i}}.start_date"
                            wire:model.lazy="items.{{$i}}.start_date"
                        />
                        <x-input-error for="items.{{$i}}.start_date" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="items.{{$i}}.end_date" value="Date de fin" />
                        <x-input
                            name="items.{{$i}}.end_date"
                            type="date"
                            class="w-full"
                            id="items.{{$i}}.end_date"
                            wire:model.lazy="items.{{$i}}.end_date"
                        />
                        <x-input-error for="items.{{$i}}.end_date" class="mt-2" />
                    </div>
                </div>
            </div>
        </fieldset>
        @if(!$loop->last)
            <div class="border-b border-dashed my-3"></div>
        @endif
    @endforeach

    @if ($this->canAddMoreItems())
        <div wire:click.prevent="addItem" class="m-5 underline cursor-pointer text-gray-500 hover:text-gray-800">
            + Ajouter une attestation
        </div>
    @endif
</div>
