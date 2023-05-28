<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('request.showApprovalForm', $request) }}
</x-slot>

<section>
    <x-section-header title="Valider une demande">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" href="{{ route('request.show', $request) }}">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <div class="shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
            <dl>
                <div class="flex">
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Code</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $request->broker->code}}</dd>
                    </div>
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Nom</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $request->broker->name}}</dd>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Emise le</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $request->created_at->format('d/m/Y') }}</dd>
                    </div>
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Statut</dt>
                        <dd>
                            <x-badge class="{{ $request->state->textColor() }} {{ $request->state->color() }}">
                                {{ $request->state->label() }}
                            </x-badge>
                        </dd>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Type d'imprimés</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $request->attestationType->name }}</dd>
                    </div>
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Quantité demandée</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $request->quantity }}</dd>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Quantité approuvée</dt>
                        <dd class="text-base font-normal text-gray-900">{{  $request->quantity_approved ?? 'n/a' }}</dd>
                    </div>
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Stock de l'intermédiaire</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $brokerStock }}</dd>
                    </div>
                </div>

                <div class="flex">
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Stock Disponible</dt>
                        <dd class="text-base font-normal text-gray-900">
                            {{ $availableStock }}
                        </dd>
                    </div>
                    @if($showBrownAttestationStock)
                        <div class="w-1/2 mb-6">
                            <dt class="text-sm font-medium text-gray-500">Stock Disponible (Attestations brunes)</dt>
                            <dd class="text-base font-normal text-gray-900">
                                {{ $availableBrownAttestationStock }}
                            </dd>
                        </div>
                    @endif
                </div>
                <div class="flex">
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Dernière livraison</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $lastDelivery ? $lastDelivery->created_at->format('d/m/Y') : 'Aucune' }}</dd>
                    </div>
                </div>
            </dl>
            <div class="w-full">
                <x-label for="quantity_validated" value="Entrer la quantité à valider" />
                <x-input
                    id="quantity_validated"
                    class="w-full block mt-1"
                    name="state.quantity_validated"
                    type="number"
                    wire:model.defer="state.quantity_validated"
                    wire:loading.attr="disabled"
                    wire:target="validateRequest"
                />
                <x-input-error for="state.quantity_validated" class="mt-2" />
                <x-input-error for="insufficient_stock" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
            <x-loading-button wire:target="validateRequest" wire:click="validateRequest" class="btn-primary">
                Valider
            </x-loading-button>
        </div>
    </div>
</section>
