<x-form-section submit="updateBroker">
    <x-slot name="title">
        Informations de l'intermédiaire
    </x-slot>

    <x-slot name="description">
        Toutes les informations de l'intermédiaire et le logo.
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
            <div class="mt-2" x-show="! photoPreview">
                <img src="{{ $broker->logo_url ? $broker->profile_photo_url : asset('static/images/default.jpeg')  }}" alt="{{ $broker->name }}" class="rounded-sm h-32 w-48 object-cover">
            </div>

            <!-- New Profile Photo Preview -->
            <div class="mt-2" x-show="photoPreview">
                <span class="block rounded-sm w-48 h-32"
                      x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                </span>
            </div>

            <x-button class="mt-2" type="button" x-on:click.prevent="$refs.logo.click()">
                Sélectionnez un nouveau logo
            </x-button>

            <x-input-error for="logo" class="mt-2" />
        </div>

        <!-- Broker Name -->
        <div class="col-span-6 sm:col-span-6">
            <x-label for="name" value="Nom de l'intermédiaire" />
            <x-input
                id="name"
                type="text"
                class="mt-1 block w-full"
                wire:model.defer="state.name"
            />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-label for="email" value="Adresse E-mail" />
            <x-input
                id="email"
                class="mt-1 block w-full"
                name="state.email"
                wire:model.defer="state.email"
                wire:loading.attr="disabled"
                wire:target="saveBroker"
            ></x-input>
            <x-input-error for="state.email" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-label for="contact" value="Contact" />
            <x-input
                id="contact"
                class="mt-1 block w-full"
                name="state.contact"
                wire:model.defer="state.contact"
                wire:loading.attr="disabled"
                wire:target="saveBroker"
            ></x-input>
            <x-input-error for="state.contact" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6">
            <x-label for="address" value="Adresse" />
            <x-input
                id="address"
                class="mt-1 block w-full"
                name="state.address"
                wire:model.defer="state.address"
                wire:loading.attr="disabled"
                wire:target="saveBroker"
            ></x-input>
            <x-input-error for="state.address" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            Enregistré
        </x-action-message>

        <x-loading-button class="btn-primaryry" type="submit">
            Enregistrer
        </x-loading-button>
    </x-slot>
</x-form-section>
