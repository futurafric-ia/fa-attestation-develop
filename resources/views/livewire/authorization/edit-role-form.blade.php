<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('backend.roles.edit', $role) }}
</x-slot>

<section>
    <x-section-header title="Editer un rôle">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="backend.roles.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-form-card submit="saveRole">
        <x-slot name="form">
            <fieldset  class="mt-4 mb-2 p-5 border border-gray-200">
                <legend class="text-base text-gray-900 font-semibold">
                    Informations du rôle
                </legend>

                <div class="grid grid-cols-1 gap-4">

                    <div class="col-span-1 sm:col-span-1">
                        <x-label for="name" value="Libelle du rôle" />
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
                        <x-input-error for="state.name" class="mt-2" />
                    </div>

                    <div class="col-span-1 sm:col-span-1">
                        <x-label for="description" value="Veuillez entrer une description relative au rôle:" />
                        <x-textarea
                            id="description"
                            name="state.description"
                            wire:model.defer="state.description"
                            wire:loading.attr="disabled"
                            wire:model.defer="state.description"
                        >
                        </x-textarea>
                        <x-input-error for="state.description" class="mt-2" />
                    </div>

                </div>
            </fieldset>
            @if ($role->id !== \Domain\Authorization\Models\Role::SUPER_ADMIN)
            <fieldset class="mt-4 mb-2 p-5 border border-gray-200">
                <legend class="text-base text-gray-900 font-semibold">
                    Permissions à accordées à ce rôle
                </legend>
                @foreach($permissions->chunk(2) as $chunkedPermissions)
                    <div class="grid grid-cols-2 gap-x-5 mb-2">
                        @foreach($chunkedPermissions as $permission)
                            <ul class="permission-tree m-0 p-0 list-unstyled col-span-1">
                                <li>
                                    <input
                                        class="form-checkbox indeterminate-checkbox" type="checkbox"
                                        wire:model.defer="rolePermissions"
                                        value="{{ $permission->id }}"
                                        id="{{ $permission->id }}"
                                        {{ in_array($permission->id, $rolePermissions ?? [], true) ? 'checked' : '' }}
                                    />
                                    <label for="{{ $permission->id }}">
                                        {{ $permission->description ?? $permission->name }}
                                    </label>
                                    @if($permission->children->count())
                                        <ul class="list-unstyled ml-4">
                                            @foreach($permission->children as $chilPermission)
                                                <li>
                                                    <input
                                                        class="form-checkbox indeterminate-checkbox"
                                                        type="checkbox"
                                                        wire:model.defer="rolePermissions"
                                                        value="{{ $chilPermission->id }}"
                                                        id="{{ $chilPermission->id }}"
                                                        {{ in_array($chilPermission->id, $rolePermissions ?? [], true) ? 'checked' : '' }}
                                                    />
                                                    <label for="{{ $chilPermission->id }}">
                                                        {{ $chilPermission->description ?? $chilPermission->name }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            </ul>
                        @endforeach
                    </div>
                @endforeach
            </fieldset>
            @endif
        </x-slot>
        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="saveRole" type="submit">
                Soumettre
            </x-loading-button>
        </x-slot>
    </x-form-card>
</section>

@push('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
            integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
            crossorigin="anonymous"></script>
    <script src="{{ mix('build/indeterminate-checkbox.js') }}"></script>
    <script>
        IndeterminateCheckbox.init()
    </script>
@endpush
