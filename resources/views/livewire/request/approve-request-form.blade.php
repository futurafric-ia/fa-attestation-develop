<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('request.showValidationForm', $request) }}
</x-slot>

<section>
    <x-section-header title="Valider une demande">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" href="{{ route('request.show', $request) }}">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <div class="mb-4">
        <x-validation-errors field="authorization" />
    </div>

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
                        <dt class="text-sm font-medium text-gray-500">Dernière livraision</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $lastDelivery ? $lastDelivery->created_at->format('d/m/Y') : 'Aucune' }}</dd>
                    </div>
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Stock intermediaire</dt>
                        <dd class="text-base font-normal text-gray-900">{{ $brokerStock }}</dd>
                    </div>
                </div>
                <div class="flex">
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Critère taux</dt>
                        <dd>
                            <x-badge class="text-white {{ $hasConsumedMinimumQuota ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $hasConsumedMinimumQuota ? 'Conforme' : 'Non conforme' }}
                            </x-badge>
                        </dd>
                    </div>
                    <div class="w-1/2 mb-6">
                        <dt class="text-sm font-medium text-gray-500">Authorisation</dt>
                        <dd>
                            <x-badge class="text-white {{ $isAuthorized ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $isAuthorized ? 'Conforme' : 'Non conforme' }}
                            </x-badge>
                        </dd>
                    </div>
                </div>
            </dl>
            @if($canValidate)
                <div class="w-full">
                    <x-label for="quantity_approved" value="Entrer la quantité à valider" />
                    <x-input
                        id="quantity_approved"
                        class="w-full block mt-1"
                        name="state.quantity_approved"
                        type="number"
                        wire:model.defer="state.quantity_approved"
                        wire:loading.attr="disabled"
                        wire:target="approveRequest"
                    />
                    <x-input-error for="state.quantity_approved" class="mt-2" />
                    <x-input-error for="insufficient_stock" class="mt-2" />
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
            @if($canValidate)
                <x-loading-button wire:target="approveRequest" wire:click="approveRequest" class="btn-secondary">
                    Valider
                </x-loading-button>
            @else
                <x-loading-button wire:target="rejectRequest" wire:click="rejectRequest" class="ml-3 btn-red">
                    Rejéter
                </x-loading-button>
            @endif
        </div>
    </div>
</section>
