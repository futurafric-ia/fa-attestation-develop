
<section id="broker-member-manager">
    @if ($broker->users->isNotEmpty())
    <x-section-border />

        <!-- Manage Broker Members -->
        <div class="mt-10 sm:mt-0">
            <div class="flex justify-end mb-5">
                <x-button wire:click="$set('addingBrokerMember', true)" class="btn-accent">
                    Ajouter un membre
                    <x-slot name="appendIcon">
                        <x-heroicon-o-user-add class="w-5 h-5"></x-heroicon-o-user-add>
                    </x-slot>
                </x-button>
            </div>

            <x-action-section>
                <x-slot name="title">
                    Membres
                </x-slot>

                <x-slot name="description">
                    Tous les utilisateurs appartenant à cet intermédiaire.
                </x-slot>

                <!-- Broker Member List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($broker->users->sortBy('full_name') as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->last_name }}">
                                    <div class="ml-4 leading-tight flex flex-col">
                                        <div>{{ $user->full_name }}</div>
                                        <div class="text-xs text-gray-700">{{ $user->email }} @if($user->last_login_at), dernière connexion le {{ $user->last_login_at->format('d/m/Y H:i') }} @endif</div>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    @if ($logged_in_user->id !== $user->id)
                                        <!-- Update Broker Member -->
                                        <button class="cursor-pointer ml-6 text-sm focus:outline-none" wire:click="confirmEditingBrokerMember('{{ $user->id }}')">
                                            <x-heroicon-o-pencil class="text-gray-800 dark:text-gray-200 h-4 w-4" id="update-user-{{$user->id}}" />
                                            <x-tooltip content="Modifier" placement="top" append-to="#update-user-{{$user->id}}" />
                                        </button>
                                        @if (! $user->isBrokerOwner())
                                            <!-- Remove Broker Member -->
                                            <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none" wire:click="confirmBrokerMemberRemoval('{{ $user->id }}')">
                                                <x-heroicon-o-trash class="text-red-800 h-4 w-4" id="remove-user-{{$user->id}}" />
                                                <x-tooltip content="Retirer" placement="top" append-to="#remove-user-{{$user->id}}" />
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>

        <!-- Remove Broker Member Confirmation Modal -->
        <x-confirmation-modal wire:model="confirmingBrokerMemberRemoval">
            <x-slot name="title">
                Rétirer un membre
            </x-slot>

            <x-slot name="content">
                Etes vous sûr de vouloir rétirer cette personne de l'organisation ?
            </x-slot>

            <x-slot name="footer">
                <x-button  wire:click="$toggle('confirmingBrokerMemberRemoval')" wire:loading.attr="disabled">
                    Annuler
                </x-button>

                <x-button class="ml-2 btn-red" wire:click="removeBrokerMember" wire:loading.attr="disabled">
                    Rétirer
                </x-button>
            </x-slot>
        </x-confirmation-modal>

        <!-- Edit Broker Member Modal -->
        <x-dialog-modal wire:model="editingBrokerMember">
            <x-slot name="title">
                Editer un membre
            </x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-6 gap-4">

                    <div class="col-span-6 sm:col-span-6">
                        <x-label for="last_name" value="Nom" />
                        <x-input
                            id="last_name"
                            class="mt-1 block w-full"
                            name="editBrokerMemberForm.last_name"
                            wire:model.defer="editBrokerMemberForm.last_name"
                            wire:loading.attr="disabled"
                        >
                        </x-input>
                        <x-input-error for="editBrokerMemberForm.last_name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <x-label for="first_name" value="Prénoms" />
                        <x-input
                            id="first_name"
                            class="mt-1 block w-full"
                            name="editBrokerMemberForm.first_name"
                            wire:model.defer="editBrokerMemberForm.first_name"
                            wire:loading.attr="disabled"
                        >
                        </x-input>
                        <x-input-error for="editBrokerMemberForm.attestation_type_id" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-6">
                        <x-label for="email" value="E-mail" />
                        <x-input
                            id="email"
                            class="mt-1 block w-full"
                            name="editBrokerMemberForm.email"
                            type="email"
                            wire:model.defer="editBrokerMemberForm.email"
                            wire:loading.attr="disabled"
                        ></x-input>
                        <x-input-error for="editBrokerMemberForm.email" class="mt-2" />
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-button  wire:click="stopEditingBrokerMember" wire:loading.attr="disabled">
                    Annuler
                </x-button>

                <x-button class="ml-2 btn-primary" wire:click="updateBrokerMember" wire:loading.attr="disabled">
                    Enregistrer
                </x-button>
            </x-slot>
        </x-dialog-modal>

        <!-- Add Broker Member Modal -->
        <x-dialog-modal wire:model="addingBrokerMember">
            <x-slot name="title">
                Ajouter un membre
            </x-slot>

            <x-slot name="content">
                    <div class="grid grid-cols-6 gap-4">

                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="last_name" value="Nom" />
                            <x-input
                                id="last_name"
                                class="mt-1 block w-full"
                                name="addBrokerMemberForm.last_name"
                                wire:model.defer="addBrokerMemberForm.last_name"
                                wire:loading.attr="disabled"
                            >
                            </x-input>
                            <x-input-error for="addBrokerMemberForm.last_name" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="first_name" value="Prénoms" />
                            <x-input
                                id="first_name"
                                class="mt-1 block w-full"
                                name="addBrokerMemberForm.first_name"
                                wire:model.defer="addBrokerMemberForm.first_name"
                                wire:loading.attr="disabled"
                            >
                            </x-input>
                            <x-input-error for="addBrokerMemberForm.attestation_type_id" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="email" value="E-mail" />
                            <x-input
                                id="email"
                                class="mt-1 block w-full"
                                name="addBrokerMemberForm.email"
                                type="email"
                                wire:model.defer="addBrokerMemberForm.email"
                                wire:loading.attr="disabled"
                            ></x-input>
                            <x-input-error for="addBrokerMemberForm.email" class="mt-2" />
                        </div>
                    </div>
            </x-slot>

            <x-slot name="footer">
                <x-button  wire:click="$toggle('addingBrokerMember')" wire:loading.attr="disabled">
                    Annuler
                </x-button>

                <x-button class="ml-2 btn-primary" wire:click="addBrokerMember" wire:target="addBrokerMember" wire:loading.attr="disabled">
                    Enregistrer
                </x-button>
            </x-slot>
        </x-dialog-modal>
        @endif
</section>
