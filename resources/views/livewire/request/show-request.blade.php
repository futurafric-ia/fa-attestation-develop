<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('request.show', $request) }}
</x-slot>

<section>
    <x-section-header title="Détails de la demande">
        <x-slot name="actions">
            <x-button-link class="btn-secondary" route="request.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    @canany(['request.validate', 'request.approve', 'delivery.create'])
        <div class="bg-white mb-3 shadow rounded-md">
            <div class="container mx-auto px-6">
                <div class="flex items-center justify-between py-5">
                    <div>
                        <a href="#" class="inline-flex items-center text-sm text-gray-500 leading-none pb-2">
                            <i class="fas fa-angle-left"></i>
                            <span>Intermédiaire</span>
                        </a>
                        <div class="flex items-center">
                            <h1 class="px-2 text-2xl text-gray-900 font-extrabold">
                                {{ $request->broker->name }} ({{ $request->broker->code }})
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @if($request->isPending() && $logged_in_user->can('request.approve'))
                            <x-button-link class="btn-primary" href="{{ route('request.showApprovalForm', $request) }}">
                                Procéder à la validation
                            </x-button-link>
                        @endif
                        @if($request->isApproved() && $logged_in_user->can('request.validate'))
                            <x-button-link class="btn-primary" href="{{ route('request.showValidationForm', $request) }}">
                                Procéder à la validation
                            </x-button-link>
                        @endif
                        @if($request->isValidated() && $logged_in_user->can('delivery.create'))
                            <x-button-link class="btn-primary" href="{{ route('request.showDeliveryForm', $request) }}">
                                Procéder à la livraison
                            </x-button-link>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <div class="overflow-hidden flex space-x-2 mb-3">
        <div class="w-1/2 min-h-full">
            <x-card title="Détails" class="min-h-full">
                <dl>
                    @if ($logged_in_user->isBroker() && $logged_in_user->isOwnerOfBroker(broker()))
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Emise par
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ optional($request->broker->owner)->full_name }}
                            </dd>
                        </div>
                    @else
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Code
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $request->broker->code}}
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Nom
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $request->broker->name}}
                            </dd>
                        </div>
                    @endif
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                            Date demande
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $request->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                            Statut
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            <x-badge class="{{ $request->state->textColor() }} {{ $request->state->color() }}">
                                {{ $request->state->label() }}
                            </x-badge>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                            Type d'imprimés
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $request->attestationType->name }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm leading-5 font-medium text-gray-500">
                            Quantité demandée
                        </dt>
                        <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $request->quantity }}
                        </dd>
                    </div>
                    @canany(['request.validate', 'request.approve', 'delivery.create'])
                        @if ($request->quantity_approved)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Quantité approuvée
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $request->quantity_approved }}
                            </dd>
                        </div>
                        @endif
                        @if ($request->quantity_validated)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Quantité validée
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $request->quantity_validated }}
                            </dd>
                        </div>
                        @endif
                    @endcan
                    @if ($request->quantity_delivered)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                Quantité livrée
                            </dt>
                            <dd class="mt-1 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $request->quantity_delivered }}
                            </dd>
                        </div>
                    @endif
                </dl>
                @if($request->isPending() && $logged_in_user->can('request.create'))
                    <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <x-button wire:target="cancel" class="mr-3 btn-red" wire:click="cancel">
                            Annuler
                        </x-button>
                        <x-button-link class="btn-primary" href="{{ route('request.edit', $request) }}">
                            Modifier
                        </x-button-link>
                    </div>
                @endif
            </x-card>
        </div>
        <div class="w-1/2 min-h-full">
            <x-card title="Timeline" class="min-h-full">
                <ul class="p-2 sm:p-5 xl:p-6">
                    <li>
                        <div
                            class="grid md:grid-cols-12 xl:grid-cols-12 items-start relative p-3 sm:p-5 xl:p-6 overflow-hidden">
                            <h3 class="font-semibold text-gray-900 md:col-start-5 md:col-span-7 xl:col-start-5 xl:col-span-7 mb-1 ml-9 md:ml-0">
                                Demande reçue
                            </h3>
                            <time datetime="{{ $request->created_at }}"
                                  class="md:col-start-1 md:col-span-4 row-start-1 md:row-end-5 flex items-center font-medium mb-1 md:mb-0">
                                <svg viewBox="0 0 12 12" class="w-3 h-3 mr-6 overflow-visible text-primary-400">
                                    <circle cx="6" cy="6" r="6" fill="currentColor"></circle>
                                    <circle cx="6" cy="6" r="11" fill="none" stroke="currentColor"
                                            stroke-width="2"></circle>
                                    <path d="M 6 18 V 500" fill="none" stroke-width="2" stroke="currentColor"
                                          class="text-gray-200"></path>
                                </svg>
                                {{ $request->created_at->format('d/m/Y') }}
                            </time>
                        </div>
                    </li>
                    @if (! $request->aborted_at)
                        <li>
                            <div
                                class="grid md:grid-cols-12 xl:grid-cols-12 items-start relative p-3 sm:p-5 xl:p-6 overflow-hidden">
                                <h3 class="font-semibold text-gray-900 md:col-start-5 md:col-span-7 xl:col-start-5 xl:col-span-7 mb-1 ml-9 md:ml-0">
                                    Demande en cours de traitement
                                </h3>
                                <time datetime="{{ $request->approved_at }}"
                                      class="md:col-start-1 md:col-span-4 row-start-1 md:row-end-5 flex items-center font-medium mb-1 md:mb-0">
                                    <svg viewBox="0 0 12 12" class="w-3 h-3 mr-6 overflow-visible {{ $request->quantity_approved ? 'text-primary-400' : 'text-gray-300' }}">
                                        <circle cx="6" cy="6" r="6" fill="currentColor"></circle>
                                        @if($request->quantity_approved)
                                            <circle cx="6" cy="6" r="11" fill="none" stroke="currentColor"
                                                    stroke-width="2"></circle>
                                        @endif
                                        <path d="M 6 -6 V -30" fill="none" stroke-width="2" stroke="currentColor"
                                              class="text-gray-200"></path>
                                        <path d="M 6 18 V 500" fill="none" stroke-width="2" stroke="currentColor"
                                              class="text-gray-200"></path>
                                    </svg>
                                    {{ $request->approved_at ? $request->approved_at->format('d/m/Y') : 'N/A' }}
                                </time>
                            </div>
                        </li>
                        <li>
                            <div
                                class="grid md:grid-cols-12 xl:grid-cols-12 items-start relative p-3 sm:p-5 xl:p-6 overflow-hidden">
                                <h3 class="font-semibold text-gray-900 md:col-start-5 md:col-span-7 xl:col-start-5 xl:col-span-7 mb-1 ml-9 md:ml-0">
                                    Demande validée
                                </h3>
                                <time datetime="{{ $request->validated_at }}"
                                      class="md:col-start-1 md:col-span-4 row-start-1 md:row-end-5 flex items-center font-medium mb-1 md:mb-0">
                                    <svg viewBox="0 0 12 12" class="w-3 h-3 mr-6 overflow-visible {{ $request->quantity_validated ? 'text-primary-400' : 'text-gray-300' }}">
                                        <circle cx="6" cy="6" r="6" fill="currentColor"></circle>
                                        @if($request->quantity_validated)
                                            <circle cx="6" cy="6" r="11" fill="none" stroke="currentColor"
                                                    stroke-width="2"></circle>
                                        @endif
                                        <path d="M 6 -6 V -30" fill="none" stroke-width="2" stroke="currentColor"
                                              class="text-gray-200"></path>
                                        <path d="M 6 18 V 500" fill="none" stroke-width="2" stroke="currentColor"
                                              class="text-gray-200"></path>
                                    </svg>
                                    {{ $request->validated_at ? $request->validated_at->format('d/m/Y') : 'N/A' }}
                                </time>
                            </div>
                        </li>
                        <li>
                            <div
                                class="grid md:grid-cols-12 xl:grid-cols-12 items-start relative p-3 sm:p-5 xl:p-6 overflow-hidden">
                                <h3 class="font-semibold text-gray-900 md:col-start-5 md:col-span-7 xl:col-start-5 xl:col-span-7 mb-1 ml-9 md:ml-0">
                                    Demande livrée
                                </h3>
                                <time datetime="{{ $request->delivered_at }}"
                                      class="md:col-start-1 md:col-span-4 row-start-1 md:row-end-5 flex items-center font-medium mb-1 md:mb-0">
                                    <svg viewBox="0 0 12 12" class="w-3 h-3 mr-6 overflow-visible {{ $request->quantity_delivered ? 'text-primary-400' : 'text-gray-300' }}">
                                        <circle cx="6" cy="6" r="6" fill="currentColor"></circle>
                                        @if($request->quantity_delivered)
                                            <circle cx="6" cy="6" r="11" fill="none" stroke="currentColor"
                                                    stroke-width="2"></circle>
                                        @endif
                                        <path d="M 6 -6 V -30" fill="none" stroke-width="2" stroke="currentColor"
                                              class="text-gray-200"></path>
                                    </svg>
                                    {{ $request->delivered_at ? $request->delivered_at->format('d/m/Y') : 'N/A' }}
                                </time>
                            </div>
                        </li>
                    @else
                        <li>
                            <div
                                class="grid md:grid-cols-12 xl:grid-cols-12 items-start relative p-3 sm:p-5 xl:p-6 overflow-hidden">
                                <h3 class="font-semibold text-gray-900 md:col-start-5 md:col-span-7 xl:col-start-5 xl:col-span-7 mb-1 ml-9 md:ml-0">
                                    Demande annulée ou rejétée
                                </h3>
                                <time datetime="{{ $request->aborted_at }}"
                                      class="md:col-start-1 md:col-span-4 row-start-1 md:row-end-5 flex items-center font-medium mb-1 md:mb-0">
                                    <svg viewBox="0 0 12 12" class="w-3 h-3 mr-6 overflow-visible text-primary-400">
                                        <circle cx="6" cy="6" r="6" fill="currentColor"></circle>
                                        <circle cx="6" cy="6" r="11" fill="none" stroke="currentColor"
                                                stroke-width="2"></circle>
                                        <path d="M 6 -6 V -30" fill="none" stroke-width="2" stroke="currentColor"
                                              class="text-gray-200"></path>
                                    </svg>
                                    {{ $request->aborted_at->format('d/m/Y') }}
                                </time>
                            </div>
                        </li>
                    @endif
                </ul>
            </x-card>
        </div>
    </div>
    @if(! $request->isCancelled() && ! $request->isRejected())
        <x-card title="Notes" class="overflow-hidden text-gray-600">
            @if($request->notes)
                <div class="p-4 border-b">
                    <span class="font-semibold text-gray-900 block">{{ $request->broker->name }}</span>
                        {{ $request->notes }}
                        <span class="block text-sm mt-1">{{ $request->created_at->diffForHumans() }}</span>
                </div>
            @endif
            @if($notes)
                @foreach($notes as $note)
                    <div class="flex flex-col border-b">
                        <div class="w-1/6 p-6">
                            <span class="h-12 w-12 rounded-full bg-orange-500 block"></span>
                        </div>
                        <div class="w-5/6 py-5 pr-5">
                            <span
                                class="font-semibold text-gray-900 block"> {{ucfirst($note->user->isBroker() ? "{$request->broker->name }" : "{$note->user->full_name} ({$note->user->main_role_name})")}}  </span>
                            {{ $note->notes }}
                            <span class="block text-sm mt-1">{{ $note->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
            @if (! $request->isCancelled() && ! $request->isRejected() && ! $request->isDelivered())
                <div class="bg-gray-50">
                    <form wire:submit.prevent="comment">
                        <div class="p-2">
                            <x-label for="note" value="Ajouter une note"/>
                            <x-textarea id="note" name="state.note" wire:model.defer="state.note"/>
                            <x-input-error for="state.note" class="mt-2"/>
                        </div>
                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <x-loading-button class="btn-primary" type="submit">Soumettre</x-loading-button>
                        </div>
                    </form>
                </div>
            @endif
        </x-card>
    @endif
</section>
