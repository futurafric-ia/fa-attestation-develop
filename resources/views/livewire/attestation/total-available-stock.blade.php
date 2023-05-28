<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('stock.index') }}
</x-slot>

<section>
    <x-section-header title="Stock des attestations"></x-section-header>

    <div class="grid gap-6 mb-8 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
        <x-count-card
            title="{{ $attestationTypes[\Domain\Attestation\Models\AttestationType::YELLOW] }}"
            subtitle="{{ $totalAvailableStockByType[\Domain\Attestation\Models\AttestationType::YELLOW] }}"
            bg-color="bg-yellow-300"
            text-color="text-yellow-300"
            link="{{ route('attestation.index') }}"
        >
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z"/><path d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/>
                </svg>
            </x-slot>
        </x-count-card>

        <x-count-card
            title="{{ $attestationTypes[\Domain\Attestation\Models\AttestationType::GREEN] }}"
            subtitle="{{ $totalAvailableStockByType[\Domain\Attestation\Models\AttestationType::GREEN] }}"
            bg-color="bg-green-600"
            text-color="text-green-600"
            link="{{ route('attestation.index', ['filters' => ['attestation_type' => \Domain\Attestation\Models\AttestationType::GREEN, 'status' => \Domain\Attestation\States\Available::$name]]) }}"
        >
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z"/><path d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/>
                </svg>
            </x-slot>
        </x-count-card>

        <x-count-card
            title="{{ $attestationTypes[\Domain\Attestation\Models\AttestationType::BROWN] }}"
            subtitle="{{ $totalAvailableStockByType[\Domain\Attestation\Models\AttestationType::BROWN] }}"
            bg-color="bg-accent-300"
            text-color="text-accent-300"
            link="{{ route('attestation.index', ['filters' => ['attestation_type' => \Domain\Attestation\Models\AttestationType::BROWN, 'status' => \Domain\Attestation\States\Available::$name]]) }}"
        >
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="none" d="M0 0h24v24H0z"/><path d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/>
                </svg>
            </x-slot>
        </x-count-card>
    </div>
</section>
