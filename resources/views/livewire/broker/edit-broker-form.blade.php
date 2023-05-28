<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('brokers.edit', $broker) }}
</x-slot>

<section>
    <x-section-header title="{{ __('Editer un intermédiaire') }}">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="brokers.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-form-card submit="saveBroker">
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-4">

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="code" value="Code"/>
                    <x-input
                        id="code"
                        class="mt-1 block w-full"
                        name="state.code"
                        wire:model.defer="state.code"
                        wire:loading.attr="disabled"
                        wire:target="saveBroker"
                    ></x-input>
                    <x-input-error for="state.code" class="mt-2"/>
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="name" value="Raison social"/>
                    <x-input
                        id="name"
                        class="mt-1 block w-full"
                        name="state.name"
                        wire:model.defer="state.name"
                        wire:loading.attr="disabled"
                        wire:target="saveBroker"
                    ></x-input>
                    <x-input-error for="state.name" class="mt-2"/>
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="email" value="Adresse E-mail"/>
                    <x-input
                        id="email"
                        class="mt-1 block w-full"
                        name="state.email"
                        wire:model.defer="state.email"
                        wire:loading.attr="disabled"
                        wire:target="saveBroker"
                    ></x-input>
                    <x-input-error for="state.email" class="mt-2"/>
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="department" value="Département"/>
                    <x-select
                        id="department"
                        class="mt-1 block w-full"
                        name="state.department_id"
                        first-option="Choisissez le département"
                        :options="$departments"
                        wire:model.defer="state.department_id"
                        wire:loading.attr="disabled"
                        wire:target="saveBroker"
                    >
                    </x-select>
                    <x-input-error for="state.department_id" class="mt-2"/>
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="contact" value="Contact"/>
                    <x-input
                        id="contact"
                        class="mt-1 block w-full"
                        name="state.contact"
                        wire:model.defer="state.contact"
                        wire:loading.attr="disabled"
                        wire:target="saveBroker"
                    ></x-input>
                    <x-input-error for="state.contact" class="mt-2"/>
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="address" value="Adresse"/>
                    <x-input
                        id="address"
                        class="mt-1 block w-full"
                        name="state.address"
                        wire:model.defer="state.address"
                        wire:loading.attr="disabled"
                        wire:target="saveBroker"
                    ></x-input>
                    <x-input-error for="state.address" class="mt-2"/>
                </div>
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="saveBroker" type="submit">
                Enregistrer
            </x-loading-button>
        </x-slot>
    </x-form-card>
</section>
