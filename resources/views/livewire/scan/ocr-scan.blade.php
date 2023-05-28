<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('scan.ocr') }}
</x-slot>

<section>

    <x-section-header title="OCR">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" route="scan.index">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <div class="mb-4">
        <x-validation-errors field="broker" />
    </div>

    <x-action-message on="saved">
        <x-alert title="Opération réussie !">
            Le scan a démarré avec succès !
        </x-alert>
    </x-action-message>

    <x-form-card submit="runScan">
        <x-slot name="form">
            <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                <div class="pr-6 pb-8 w-full {{ $canChooseAttestationState ? 'lg:w-1/3' : 'lg:w-1/2' }}">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="broker_id" value="Intermédiaire" />
                        <div class="mt-1">
                            <livewire:broker.broker-select name="broker_id" placeholder="Choississez un intermediaire" :searchable="true" />
                        </div>
                        <x-input-error for="state.broker_id" class="mt-2" />
                    </div>
                </div>

                @if ($canChooseAttestationState)
                    <div class="pr-6 pb-8 w-full lg:w-1/3">
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="attestation_state" value="Statut" />
                            <x-select
                                wire:loading.attr="disabled"
                                wire:target="runScan"
                                class="mt-1 block w-full"
                                name="state.attestation_state"
                                id="attestation_state"
                                wire:model.defer="state.attestation_state"
                                label=""
                                first-option="Choisissez un statut"
                                :options="$attestationStates->toArray()">
                            </x-select>
                            <x-input-error for="state.attestation_state" class="mt-2" />
                        </div>
                    </div>
                @endif

                <div class="pr-6 pb-8 w-full {{ $canChooseAttestationState ? 'lg:w-1/3' : 'lg:w-1/2' }}">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="attestation_type_id" value="Type d'imprimés" />
                        <x-select
                            wire:loading.attr="disabled"
                            wire:target="runScan"
                            class="mt-1 block w-full"
                            name="state.attestation_type_id"
                            id="attestation_type_id"
                            wire:model.defer="state.attestation_type_id"
                            first-option="Choisissez un type d'imprimés"
                            :options="$attestationTypes->toArray()">
                        </x-select>
                        <x-input-error for="state.attestation_type_id" class="mt-2" />
                    </div>
                </div>

                <div class="pr-6 pb-8 w-full">
                    <div
                        class="group py-2"
                        x-data="{
                            fileName: null,
                            isUploading: false,
                            hasError: false,
                            progress: 0,
                            getFilename() {
                                this.fileName = Array.from(this.$refs.selectedFile.files).map(file => file.name).join(',');
                            }
                        }
                        "
                        x-on:livewire-upload-start="isUploading = true; hasError = false; progress = 0;"
                        x-on:livewire-upload-finish="isUploading = false; progress = 0;"
                        x-on:livewire-upload-error="isUploading = false; hasError = true"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    >
                        <label class="w-full flex flex-col items-center p-4 bg-white text-gray rounded-lg tracking-wide cursor-pointer hover:bg-primary-500 border border-dashed group-hover:text-white">
                            <x-heroicon-o-cloud-upload class="w-6 h-6" />
                            <span class="mt-1 text-sm leading-normal">Cliquez ici pour sélectionner les fichiers</span>
                            <input type="file" wire:model="files" multiple class="hidden" name="files" accept="application/pdf;" x-ref="selectedFile" x-on:change="getFilename()" />
                            <span class="text-gray-700 pt-1 text-xs group-hover:text-white" id="filename" x-text="fileName"></span>
                        </label>

                        <!-- Progress bar -->
                        <div x-show="isUploading" class="my-2" x-cloak>
                            <div class="w-full" role="progressbar" aria-valuemin="0" :aria-valuenow="progress" :aria-valuemax="100">
                                <div class="flex flex-col items-end">
                                    <span class="text-xs text-gray-600 mb-1" x-text="`complêté à ${Math.round(progress/100 * 100)}%`"></span>
                                    <span class="p-1 w-full rounded-md bg-gray-200 overflow-hidden relative flex items-center">
                                        <span class="absolute h-full w-full bg-primary-500 left-0 transition-all duration-300" :style="`width: ${ progress/100 * 100 }%`"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Upload error message -->
                        <div class="text-sm leading-none py-1 text-center text-red-500 w-full mt-2" x-show="hasError" x-cloak>
                            Le chargement des fichiers a échoué, veuillez selectionner à nouveau le(s) fichier(s).
                        </div>

                        @foreach ($errors->get('files.*') as $messages)
                            <span class="p-2 font-bold text-xs text-red-500">{{ $messages[0] }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-loading-button class="btn-primary" wire:target="runScan" type="submit" wire:loading.attr='disabled' wire:loading.class='opacity-75 cursor-not-allowed'>
                Démarrer le scan
            </x-loading-button>
        </x-slot>
    </x-form-card>

</section>

@once
    @push('after-scripts')
        <script>
            window.livewire.on('livewire-select-focus-search', (data) => {
                const el = document.getElementById(`${data.name || 'invalid'}`);

                if (!el) {
                    return;
                }

                el.focus();
            });

            window.livewire.on('livewire-select-focus-selected', (data) => {
                const el = document.getElementById(`${data.name || 'invalid'}-selected`);

                if (!el) {
                    return;
                }

                el.focus();
            });
        </script>
    @endpush
@endonce
