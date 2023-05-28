<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('report.request') }}
</x-slot>


<section>

    <div style='border-bottom: 2px solid #eaeaea' class="mb-5">
        <ul class='flex cursor-pointer'>
          <li class='py-2 px-6 rounded-t-lg text-gray-500 bg-gray-200'><a href="{{ route('report.delivery') }}">Livraisons</a></li>
          <li class='py-2 px-6 bg-white rounded-t-lg'><a href="{{ route('report.request') }}">Demandes</a></li>
        </ul>
    </div>

    <x-section-header title="Rapport des demandes">
        <x-slot name="actions">
            <x-button-link class="btn-white" target="_blank" href="{{ route('report.request.pdf', $filters + ['download' => true]) }}">
                Telecharger le PDF
            </x-button-link>
        </x-slot>
    </x-section-header>

    <form wire:submit.prevent="updateReport" x-data="{dateRangeVisible: false}" x-ref="report_form">
        <div class="grid gap-8 md:grid-cols-12">
            <div class="col-span-8 md:col-span-4">
                <div class="grid grid-cols-1">
                    <div>
                        <x-label for="in_the_period" value="Période prédéfinie" />
                        <x-select
                            id="in_the_period"
                            class="mt-1 block w-full"
                            name="state.in_the_period"
                            first-option="Choississez une période"
                            :options="$dateRanges"
                            wire:model.defer="state.in_the_period"
                            wire:loading.attr="disabled"
                            wire:target="updateReport"
                            x-ref="in_the_period"
                            x-on:change="dateRangeVisible = $refs.in_the_period.value === 'custom'"
                        >
                        </x-select>
                        <x-input-error for="state.in_the_period" class="mt-2" />
                    </div>
                </div>
                <div class="grid grid-cols-1 mt-4" x-show="dateRangeVisible" x-cloak>
                    <div class="w-full relative">
                        <x-label for="between_date" value="Effectué dans la période du" />
                        <x-date-picker wire:model.defer="state.between_date" class="mt-1 block w-full" id="between_date" autocomplete="off" mode="range"></x-date-picker>
                        <x-input-error for="state.between_date" class="mt-2" />
                    </div>
                </div>
                <div class="flex items-center space-x-2 justify-between">
                    <div>
                        <x-button class="mt-0 md:mt-8 w-full" type="button" x-on:click="$refs.report_form.reset()">
                            Réinitialiser
                        </x-button>
                    </div>
                    <div>
                        <x-loading-button class="btn-primary mt-0 md:mt-8 w-full" type="submit">
                            Appliquer
                        </x-loading-button>
                    </div>
                </div>
            </div>
            <div class="col-span-8 relative">
                <div
                    class="absolute inset-0 bg-gray opacity-50 h-full flex flex-col items-center justify-center"
                    id="iframe-overlay"
                >
                    <div class="text-white font-semibold flex flex-col items-center justify-center">
                        <div>
                            <x-heroicon-o-refresh class="h-10 w-10 animate-spin" />
                        </div>
                        <div>
                            Chargement du document en cours
                        </div>
                    </div>
                </div>
                <iframe id="request-iframe" class="w-full h-screen border-gray-100 border-solid rounded md:flex" src="{{ route('report.request.pdf', $filters) }}"></iframe>
            </div>
        </div>
    </form>

</section>

@push('after-scripts')
    <script>
        $invoiceIframe = document.querySelector('#request-iframe');
        $invoiceIframe.addEventListener('load', function() {
            $overlay = document.querySelector('#iframe-overlay')

            if ($overlay) {
                $overlay.style.display = 'none'
            }
        })
    </script>
@endpush
