<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('users.show', $user) }}
</x-slot>

<section>
    <x-section-header title="Détails">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" permission="user.list" route="users.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <x-section-title>
            <x-slot name="title">Informations de l'utilisateur</x-slot>
            <x-slot name="description">Les informations de l'utilisateur</x-slot>
        </x-section-title>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-8 gap-6">
                        <div class="col-span-6 sm:col-span-8">
                            <div class="flex">
                                <div class="w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Nom</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $user->full_name }}</dd>
                                </div>
                                <div class="w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $user->email }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-8">
                            <div class="flex">
                                <div class="w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $user->main_role_name }}</dd>
                                </div>
                                <div class="w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        <x-badge class="text-gray-50 {{ $user->active ? 'bg-green-500' : 'bg-red-500' }}">
                                            {{ $user->active ? 'Actif' : 'Inactif' }}
                                        </x-badge>
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-8">
                            <div class="flex">
                                <div class="w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $user->address }}</dd>
                                </div>
                                <div class="w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Contact</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $user->contact }}</dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-8">
                            <div class="flex">
                                <div class="w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Dernière connexion</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}</dd>
                                </div>
                                <div class="w-1/2">
                                    <dt class="text-sm font-medium text-gray-500">Dernière addresse IP connue</dt>
                                    <dd class="text-sm font-normal text-gray-900">
                                        {{ $user->last_login_ip ?? 'Aucune' }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-8">
                            @if($user->main_role->hasDepartment())
                            <div class="flex">
                                <div class="w-full">
                                    <dt class="text-sm font-medium text-gray-500">Service</dt>
                                    <dd class="text-sm font-normal text-gray-900">{{ $user->main_department->name }}</dd>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-section-border></x-section-border>

    <!-- Manage API Tokens -->
    <div class="mt-10 sm:mt-0">
        <x-action-section>
            <x-slot name="title">
                Gestion de l'accès API
            </x-slot>

            <x-slot name="description">
                Vous pouvez gérer la clé API de ce utilisateur.
            </x-slot>

            <!-- API Token List -->
            <x-slot name="content">
                <div
                    x-data="{ copyToClipboard() {
                        const copyText = document.querySelector('#userApiKey')
                        copyText.select()
                        copyText.setSelectionRange(0, 99999)
                        document.execCommand('copy')
                    } }"
                    class="flex flex-col space-y-3"
                >
                    <input readonly class="bg-gray-50 p-3 overflow-hidden" value="{{ $user->api_token }}" id="userApiKey" />

                    <div class="flex items-center">
                        <x-button class="mr-2" @click="copyToClipboard()" >
                            Copier
                        </x-button>

                        <x-loading-button class="mr-2 btn-secondary" wire:target="regenerateApiKey" wire:click="regenerateApiKey()">
                            Regénérer
                        </x-loading-button>

                        <x-action-message on="api_key_saved">
                            <span>Clé regénérée avec succès</span>
                        </x-action-message>
                    </div>
                </div>
            </x-slot>
        </x-action-section>
    </div>
</section>
