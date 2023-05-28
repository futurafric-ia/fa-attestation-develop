<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('backend.departments.edit', $department) }}
</x-slot>

<section>
    <x-section-header title="Editer un département">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="backend.departments.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-form-card submit="saveDepartment">
        <x-slot name="form">
            <div class="grid grid-cols-1 gap-4">
                <div class="col-span-1 sm:col-span-1">
                    <x-label for="name" value="Libéllé du département"/>
                    <x-input
                        id="name"
                        class="mt-1 block w-full"
                        name="state.name"
                        type="text"
                        wire:model.defer="state.name"
                        wire:loading.attr="disabled"
                        wire:target="saveDepartment"
                    >
                    </x-input>
                    <x-input-error for="state.name" class="mt-2"/>
                </div>

                <div class="col-span-1 sm:col-span-1">
                    <x-label for="description" value="Veuillez entrer une description relative au département:"/>
                    <x-textarea
                        id="description"
                        name="state.description"
                        wire:model.defer="state.description"
                        wire:loading.attr="disabled"
                        wire:target="saveDepartment"
                    >
                    </x-textarea>
                    <x-input-error for="state.description" class="mt-2"/>
                </div>

            </div>
        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="saveDepartment" type="submit">
                Soumettre
            </x-loading-button>
        </x-slot>
    </x-form-card>
</section>
