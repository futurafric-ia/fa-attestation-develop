<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('brokers.show', $broker->id) }}
</x-slot>

<section>
    <x-section-header title="Détails">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="brokers.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-action-section>
        <x-slot name="title">
            Informations de l'intermédiaire
        </x-slot>

        <x-slot name="description">
            Les informations de cet intermédiaire tels que son nom, son adresse.
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-8 gap-6">
                <div class="col-span-6 sm:col-span-8">
                    <div class="flex">
                        <div class="w-1/2">
                            <dt class="text-sm font-medium text-gray-500">Logo</dt>
                            <dd class="text-sm font-normal text-gray-900">
                                @if($broker->logo_url)
                                    <img src="{{ $broker->logo_url ?? 'Non défini' }}" alt="Logo">
                                @else
                                    Non Défini
                                @endif
                            </dd>
                        </div>
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Code</dt>
                            <dd class="text-sm font-normal text-gray-900">{{ $broker->code }}</dd>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-8">
                    <div class="flex">
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Raison Social</dt>
                            <dd class="text-sm font-normal text-gray-900">
                                {{ $broker->slug }}
                            </dd>
                        </div>
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm font-normal text-gray-900">
                                {{ $broker->email }}
                            </dd>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-8">
                    <div class="flex">
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                            <dd class="text-sm font-normal text-gray-900">
                                {{ $broker->address }}
                            </dd>
                        </div>
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Contact</dt>
                            <dd class="text-sm font-normal text-gray-900">
                                {{ $broker->contact }}
                            </dd>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-8">
                    <div class="flex">
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Département</dt>
                            <dd class="text-sm font-normal text-gray-900">{{ $broker->department->name }}</dd>
                        </div>
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Autorisation de demande</dt>
                            <dd class="text-sm font-normal text-gray-900">
                                <x-badge class="text-gray-50 {{ $broker->active ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ $broker->active ? 'Autorisé' : 'Non Autorisé' }}
                                </x-badge>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-action-section>

    <x-section-border></x-section-border>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <x-section-title>
            <x-slot name="title">Statistiques</x-slot>
            <x-slot name="description">Les statistiques de cet intermédiaire</x-slot>
        </x-section-title>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-2">
                <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                    <div
                        class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                        <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path
                                d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.001a.996.996 0 0 1-.993.999H3.993A.996.996 0 0 1 3 20.001V10zm16 0H5v9h14v-9zM4 5v3h16V5H4zm5 7h6v2H9v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Demandes effectuées
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $totalRequestCount }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center p-4 bg-white rounded-lg shadow dark:bg-gray-800">
                    <div
                        class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                        <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 24 24">
                            <path fill="none" d="M0 0h24v24H0z"/>
                            <path
                                d="M17 8h3l3 4.056V18h-2.035a3.5 3.5 0 0 1-6.93 0h-5.07a3.5 3.5 0 0 1-6.93 0H1V6a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2zm0 2v3h4v-.285L18.992 10H17z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                            Demandes livrées
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $totalRequestDeliveredCount }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="py-4">
                <span class="font-bold">Dernières livraisons</span>
            </div>
            <x-table
                hover
                no-shadow
                class="shadow bg-white"
                :columns="[
                    'Type d\'imprimés' => 'attestationType.name',
                    'Date de livraison' => fn ($item) => optional($item->delivered_at)->format('d/m/Y'),
                    'Quantité' => 'quantity'
                ]"
                :rows="$latestDeliveries"
            >
            </x-table>
        </div>
    </div>

    <x-section-border></x-section-border>

    <x-form-section submit="updateAuthorization"
                    x-data="{ consomptionThresholdEnabled: '{{ $enableConsomptionThreshold }}' }">
        <x-slot name="title">
            Autorisation de demande
        </x-slot>

        <x-slot name="description">
            Les paramètres concernant l'autorisation de demandes de cet intermédiaire.
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6 sm:col-span-8">
                <x-checkbox
                    label="Autoriser à faire des demandes d'attestations"
                    name="authorize_request"
                    wire:model.defer="authorizeRequest"
                />
                <x-input-error for="authorizeRequest" class="mt-2"/>
            </div>

            <div class="col-span-6 sm:col-span-8">
                <x-checkbox
                    label="Activer le critère taux"
                    name="enable_consomption_threshold"
                    wire:model.defer="enableConsomptionThreshold"
                    @change="consomptionThresholdEnabled = $event.target.checked;"
                />
                <x-input-error for="enableConsomptionThreshold" class="mt-2"/>
            </div>

            <!-- Consommation minimale -->
            <div class="col-span-6 sm:col-span-8">
                <x-label for="minimum_consumption_percentage" value="Consommation minimale (en %)"/>
                <x-input
                    id="minimum_consumption_percentage"
                    name="state.minimum_consumption_percentage"
                    type="number" class="mt-1 block w-full"
                    wire:model.defer="state.minimum_consumption_percentage"
                    x-bind:disabled="consomptionThresholdEnabled == false"
                    class="disabled:opacity-25 w-full block"
                />
                <x-input-error for="state.minimum_consumption_percentage" class="mt-2"/>
            </div>

            <!-- Commentaire -->
            <div class="col-span-6 sm:col-span-8">
                <x-label for="notes" value="Commentaire"/>
                <x-textarea id="notes" name="state.notes" class="mt-1 block w-full" wire:model.defer="state.notes"/>
                <x-input-error for="state.notes" class="mt-2"/>
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="mr-3" on="authorizationSaved">
                Enregistré.
            </x-action-message>

            <x-loading-button class="btn-primary" type="submit" wire:loading.class="opacity-25" wire:loading.attr="disabled">
                Enregistrer
            </x-loading-button>
        </x-slot>
    </x-form-section>

    <livewire:settings.broker-member-manager :broker="$broker">

</section>
