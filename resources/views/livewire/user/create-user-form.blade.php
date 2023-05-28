<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('users.create') }}
</x-slot>

<section>
    <x-section-header title="Créer un utilisateur">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" permission="user.list" route="users.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-form-card submit="saveUser" x-data="{ selectedRole: '{{$roleId}}', roles: '{{$rolesWithDepartments}}' }">
        <x-slot name="form">
            <div class="grid grid-cols-8 gap-4">

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="identifier" value="Matricule" />
                    <x-input
                        id="identifier"
                        class="mt-1 block w-full"
                        name="state.identifier"
                        wire:model.defer="state.identifier"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    ></x-input>
                    <x-input-error for="state.identifier" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="email" value="Adresse E-mail" />
                    <x-input
                        id="email"
                        class="mt-1 block w-full"
                        name="state.email"
                        wire:model.defer="state.email"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    ></x-input>
                    <x-input-error for="state.email" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="last_name" value="Nom" />
                    <x-input
                        id="last_name"
                        class="mt-1 block w-full"
                        name="state.last_name"
                        wire:model.defer="state.last_name"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    ></x-input>
                    <x-input-error for="state.last_name" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="first_name" value="Prénoms" />
                    <x-input
                        id="first_name"
                        class="mt-1 block w-full"
                        name="state.first_name"
                        wire:model.defer="state.first_name"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    ></x-input>
                    <x-input-error for="state.first_name" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="role" value="Rôle" />
                    <x-select
                        id="role"
                        class="mt-1 block w-full"
                        name="state.role_id"
                        first-option="Choisissez le rôle"
                        :options="$roles"
                        x-on:input="selectedRole = $event.target.value"
                        wire:model.defer="roleId"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    >
                    </x-select>
                    <x-input-error for="state.role_id" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="department" value="Département" />
                    <x-select
                        id="department"
                        class="mt-1 block w-full disabled:opacity-50"
                        name="state.department_id"
                        first-option="Choisissez le département"
                        :options="$departments"
                        x-bind:disabled="!selectedRole || (selectedRole && !roles.includes(selectedRole))"
                        x-bind:class="{ 'cursor-not-allowed': !selectedRole || (selectedRole && !roles.includes(selectedRole)) }"
                        wire:model.defer="departmentId"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    >
                    </x-select>
                    <x-input-error for="state.department_id" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="contact" value="Contact" />
                    <x-input
                        id="contact"
                        class="mt-1 block w-full"
                        name="state.contact"
                        wire:model.defer="state.contact"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    ></x-input>
                    <x-input-error for="state.contact" class="mt-2" />
                </div>

                <div class="col-span-4 sm:col-span-4">
                    <x-label for="address" value="Adresse" />
                    <x-input
                        id="address"
                        class="mt-1 block w-full"
                        name="state.address"
                        wire:model.defer="state.address"
                        wire:loading.attr="disabled"
                        wire:target="saveUser"
                    ></x-input>
                    <x-input-error for="state.address" class="mt-2" />
                </div>

            </div>
        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="saveUser" type="submit">
                Enregistrer
            </x-loading-button>
        </x-slot>
    </x-form-card>
</section>
