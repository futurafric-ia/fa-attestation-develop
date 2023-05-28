<x-form-section submit="updateBroker">
    <x-slot name="title">
        Informations de Saham
    </x-slot>

    <x-slot name="description">
        Toutes les informations de Saham et le logo.
    </x-slot>

    <x-slot name="form">
        <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-6">
            <!-- Profile Photo File Input -->
            <input type="file" class="hidden"
                        wire:model="logo"
                        x-ref="logo"
                        x-on:change="
                                photoName = $refs.logo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.logo.files[0]);
                        " />

            <x-label for="logo" value="Logo" />

            <!-- Current Profile Photo -->
            <div class="mt-2  w-100" x-show="! photoPreview">
                <img src="{{ url('storage/logo_saham.jpg') }}" alt="logo_saham" class="w-full h-32 object-cover">
            </div>

            <!-- New Profile Photo Preview -->
            <div class="mt-2 w-100" x-show="photoPreview">
                <span class="block w-full h-32"
                      x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <x-button class="mt-2 btn-accent" type="button" x-on:click.prevent="$refs.logo.click()">
                Sélectionnez un nouveau logo
            </x-button>

            <x-input-error for="logo" class="mt-2" />
        </div>


    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            Enregistré
        </x-action-message>

        <x-loading-button type="submit" class="btn-secondary" wire:target="updateBroker">
            Enregistrer
        </x-loading-button>
    </x-slot>
</x-form-section>
