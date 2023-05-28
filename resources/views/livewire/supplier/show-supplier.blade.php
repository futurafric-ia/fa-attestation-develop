<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('suppliers.show', $supplier) }}
</x-slot>

<section>
    <x-section-header title="Détails">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="suppliers.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <x-action-section>
        <x-slot name="title">
            Informations du fournisseur
        </x-slot>

        <x-slot name="description">
            Les informations du fournisseur tel que son nom, son adresse.
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-8 gap-6">
                <div class="col-span-6 sm:col-span-8">
                    <div class="flex">
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Code</dt>
                            <dd class="text-sm font-normal text-gray-900">{{ $supplier->code }}</dd>
                        </div>
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Nom</dt>
                            <dd class="text-sm font-normal text-gray-900">{{ $supplier->name }}</dd>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-8">
                    <div class="flex">
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                            <dd class="text-sm font-normal text-gray-900">{{ $supplier->address }}</dd>
                        </div>
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Dernière livraison</dt>
                            <dd class="text-sm font-normal text-gray-900">
                                {{ $latestSupplies->last()->received_at ?? 'Aucune' }}
                            </dd>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 sm:col-span-8">
                    <div class="flex">
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">E-mail</dt>
                            <dd class="text-sm font-normal text-gray-900">{{ $supplier->email }}</dd>
                        </div>
                        <div class="w-1/2 ">
                            <dt class="text-sm font-medium text-gray-500">Contact</dt>
                            <dd class="text-sm font-normal text-gray-900">{{ $supplier->contact }}</dd>
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
            <x-slot name="description">Les statistiques de ce fournisseur.</x-slot>
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
                            Total d'approvisionnements
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $totalSupplyCount }}
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
                            Total d'attestations en stock
                        </p>
                        <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                            {{ $totalAvailableAttestationCount }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="py-4">
                <span class="font-bold">Derniers approvisionnements</span>
            </div>
            <x-table
                class="shadow"
                :columns="[
                    'Type d\'imprimés' => fn($item) => $item->attestationType->name,
                    'Date de reception' => fn($item) => $item->received_at->format('d/m/Y'),
                    'Date d\'enregistrement' => fn($item) => $item->created_at->format('d/m/Y'),
                    'Quantité' => 'quantity'
                ]"
                :rows="$latestSupplies"
            >
            </x-table>
        </div>
    </div>
</section>
