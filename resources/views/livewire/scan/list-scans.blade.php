<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('scan.index') }}
</x-slot>

<section>
    <x-section-header title="Scans">

        <x-slot name="actions">
            @if ($logged_in_user->can('delivery.create'))
                <x-loading-button class="bg-secondary-900 text-white hover:bg-opacity-75" wire:click="$toggle('openModal')" wire:loading.class="opacity-25" wire:loading.attr="disabled">
                    Effectuer un scan
                </x-loading-button>

                <x-dialog-modal wire:model="openModal">
                    <x-slot name="title">
                        Choississez une méthode de scan
                    </x-slot>

                    <x-slot name="content">
                        <div class="flex justify-around items-center p-5 space-x-2 text-white">
                            <a href="{{ route('scan.ocr') }}"
                                class="border rounded shadow p-2 text-indigo-500 hover:shadow-lg h-40 w-40 flex items-center justify-center">
                                OCR
                            </a>
                            <a href="{{ route('scan.manual') }}"
                                class="border rounded shadow hover:shadow-lg p-2 text-orange-400 h-40 w-40 flex items-center justify-center">
                                Saisie manuelle
                            </a>
                        </div>
                    </x-slot>

                </x-dialog-modal>
            @else
                <x-button-link class="btn-secondary" href="{{ route('scan.ocr') }}">
                    Effectuer un scan
                </x-button-link>
            @endif
        </x-slot>

    </x-section-header>

    <livewire:scan.scans-table />
</section>
