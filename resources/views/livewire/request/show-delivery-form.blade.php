<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('request.showDeliveryForm', $request) }}
</x-slot>

<section x-data="{ steps: $wire.get('steps'), step: @entangle('step') }">
    <x-section-header title="Faire une livraison">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" href="{{ route('request.show', $request) }}">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <div class="mb-4">
        <x-validation-errors></x-validation-errors>
    </div>

    <x-form-card submit="deliver">
        <x-slot name="form">
            <div>
                @if ($request->isRelated())
                <!-- Top Navigation -->
                <div class="border-b-2 py-4">
                    <div class="uppercase tracking-wide text-xs font-bold text-gray-500 mb-1 leading-tight" x-text="`Etape: ${step} sur {{ $steps }}`"></div>
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <div x-show="step === 1">
                                <div class="text-lg font-bold text-gray-700 leading-tight">Demande principale</div>
                            </div>
                            <div x-show="step === 2">
                                <div class="text-lg font-bold text-gray-700 leading-tight">Demande associée</div>
                            </div>
                        </div>
                        <div class="flex items-center md:w-64">
                            <div class="w-full bg-gray-100 rounded-full mr-2">
                                <div class="rounded-full bg-green-500 text-xs leading-none h-2 text-center text-white" :style="'width: '+ parseInt(step / steps * 100) +'%'"></div>
                            </div>
                            <div class="text-xs w-10 text-gray-600" x-text="parseInt(step / steps * 100) +'%'"></div>
                        </div>
                    </div>
                </div>
                <!-- /Top Navigation -->
                @endif

                <div class="px-6 py-5">
                    <dl>
                        <div class="flex">

                            <div class="w-1/2 mb-6">
                                <dt class="text-sm font-medium text-gray-500">Intermédiaire</dt>
                                <dd class="text-base font-normal text-gray-900">{{ $request->broker->name }} ({{$request->broker->code}})</dd>
                            </div>
                            <div class="w-1/2 mb-6">
                                <dt class="text-sm font-medium text-gray-500">Emise le</dt>
                                <dd class="text-base font-normal text-gray-900">{{ $request->created_at->format('d/m/Y') }}</dd>
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
                                <dt class="text-sm font-medium text-gray-500">Quantité à livrer</dt>
                                <dd class="text-base font-normal text-gray-900">{{ $request->quantity_validated }}</dd>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="w-1/2 mb-6">
                                <dt class="text-sm font-medium text-gray-500">Stock Disponible</dt>
                                <dd class="text-base font-normal text-gray-900">
                                    {{ $availableStock }}
                                </dd>
                            </div>
                            <div class="w-1/2 mb-6">
                                <dt class="text-sm font-medium text-gray-500">Stock intermédiaire</dt>
                                <dd class="text-base font-normal text-gray-900">{{ $brokerStock }}</dd>
                            </div>
                        </div>
                        @if ($showBrownAttestationStock)
                        <div class="flex">
                            <div class="w-full mb-6">
                                <dt class="text-sm font-medium text-gray-500">Stock Disponible demande associée</dt>
                                <dd class="text-base font-normal text-gray-900">
                                    {{ $availableBrownAttestationStock }}
                                </dd>
                            </div>
                        </div>
                        @endif
                    </dl>
                </div>

                @if ($request->isRelated())
                    <!-- Step Content -->
                    <div>
                        <div x-show.transition.in="step === 1">
                            <livewire:attestation.attestation-ranges :type="$request->attestation_type_id" />
                        </div>
                        <div x-show.transition.in="step === 2">
                            <livewire:attestation.attestation-ranges :type="$request->attestation_type_id" />
                        </div>
                    </div>
                    <!-- / Step Content -->
                @else
                    <livewire:attestation.attestation-ranges :type="$request->attestation_type_id" />
                @endif
            </div>
        </x-slot>

        <x-slot name="actions">
            <div class="flex items-center space-x-2">
                @error('attestation_range')
                    <x-action-message>
                        La livraison a échouée.
                    </x-action-message>
                @enderror
                @if ($request->isRelated())
                    <x-button x-show="step > 1" @click="step--" >Précedent</x-button>
                    <x-button x-show="step < 2" @click="step++" >Suivant</x-button>
                    <x-loading-button class="btn-primary" wire:target="deliver" type="submit" x-show="step === 2">Livrer</x-loading-button>
                @else
                    <x-loading-button class="btn-primary" wire:target="deliver" type="submit">Livrer</x-loading-button>
                @endif
            </div>
        </x-slot>
    </x-form-card>
</section>
