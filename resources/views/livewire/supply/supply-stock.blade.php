<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('supply.supplyStockForm') }}
</x-slot>

<section>
    <x-section-header title="Approvisionner le stock">
        <x-slot name="actions">
            <x-button-link class="text-white hover:bg-opacity-75 btn-secondary" route="supply.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <div class="mb-4">
    <x-validation-errors field="attestation_range"></x-validation-errors>
    </div>

    <x-form-card submit="supplyStock">
        <x-slot name="form">
            <div
                class="p-8 -mr-6 -mb-8 flex flex-wrap"
                x-data="{
                    rangeStart: 0,
                    rangeEnd: 0,
                    total() {
                        if (Math.sign(this.rangeStart) <= 0 || Math.sign(this.rangeEnd) <= 0) {
                            return 0
                        }

                        if (this.rangeStart !== 0 && this.rangeEnd !== 0) {
                            const total = this.rangeEnd - this.rangeStart + 1
                            return Math.sign(total) < 0 ? 0 : total
                        }

                        return 0
                    }
                }
            ">

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="supplier_id" value="Fournisseur" />
                        <x-select
                            wire:loading.attr="disabled"
                            wire:target="supplyStock"
                            id="supplier_id"
                            name="state.supplier_id"
                            first-option="Choisissez le fournisseur"
                            :options="$suppliers"
                            wire:model.defer="state.supplier_id"
                            class="mt-1 block w-full"
                        >
                        </x-select>
                        <x-input-error for="state.supplier_id" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="attestation_type_id" value="Type d'imprimés" />
                        <x-select
                            wire:loading.attr="disabled"
                            wire:target="supplyStock"
                            id="attestation_type_id"
                            name="state.attestation_type_id"
                            first-option="Choisissez le type d'imprimés"
                            :options="$attestationTypes"
                            id="attestation_type"
                            wire:model.defer="state.attestation_type_id"
                            class="mt-1 block w-full"
                        >
                        </x-select>
                        <x-input-error for="state.attestation_type_id" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="range_start" value="Début série" />
                        <x-input
                            name="state.range_start"
                            id="range_start"
                            type="number"
                            placeholder="Entrer la fin de la série"
                            class="mt-1 block w-full"
                            wire:loading.attr="disabled"
                            wire:target="supplyStock"
                            wire:model="state.range_start"
                            x-on:input="rangeStart = +$event.target.value"
                        ></x-input>
                        <x-input-error for="state.range_start" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="range_end" value="Fin série" />
                        <x-input
                            wire:loading.attr="disabled"
                            wire:target="supplyStock"
                            name="state.range_end"
                            id="range_end"
                            x-on:input="rangeEnd = +$event.target.value"
                            type="number"
                            placeholder="Entrer la fin de la série"
                            wire:model.defer="state.range_end"
                            class="mt-1 block w-full"
                        ></x-input>
                        <x-input-error for="state.range_end" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div>
                        <x-label for="received_at" value="Date de reception" />
                        <x-input
                            wire:loading.attr="disabled"
                            wire:target="supplyStock"
                            name="state.received_at"
                            id="received_at"
                            type="date"
                            wire:model.defer="state.received_at"
                            class="mt-1 block w-full"
                        ></x-input>
                        <x-input-error for="state.received_at" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full lg:w-1/2">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="received_at" value="Quantité totale" />
                        <span class="text-lg" x-text="total()">0</span>
                    </div>
                </div>

            </div>
        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="supplyStock" type="submit">
                Soumettre
            </x-loading-button>
        </x-slot>
    </x-form-card>

</section>
