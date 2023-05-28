@section('title', 'Tableau de bord')

<section>
    <x-dashboard-header>
        <x-slot name="actions">
            <a href="{{ route('scan.ocr') }}" class="inline-flex items-center px-3 py-3 hover:text-white text-blue-700 hover:bg-primary-900 border border-primary-900 rounded-full font-semibold text-xs uppercase tracking-widest active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring-gray transition ease-in-out duration-150">
                Scanner
                <span class="ml-3">
                    <x-heroicon-o-printer class="w-5 h-5" />
                </span>
            </a>
        </x-slot>
    </x-dashboard-header>

    <div class="md:mx-auto sm:px-6 md:px-12 md:py-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Vue d'ensemble</h2>
        </div>
        <div class="mt-2" x-data="{ showedCardIndex: 1 }">
            <div class="grid gap-6 mb-8 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                <x-count-card
                    title="Attestations servies et produites"
                    subtitle="{{ $totalSucceddedScan }}"
                    bg-color="bg-blue-600"
                    text-color="text-blue-600"
                >
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/><path d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/>
                        </svg>
                    </x-slot>
                    <x-slot name="action">
                        <div class="w-full bg-blue-600 rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                            <button class="focus:outline-none" @click="showedCardIndex = 1;">Voir details</button>
                        </div>
                    </x-slot>
                </x-count-card>

                <x-count-card
                    title="Attestations litigieuses"
                    subtitle="{{ $totalScanMismatches }}"
                    bg-color="bg-secondary-800"
                    text-color="text-secondary-800"
                    link="#"
                >
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/><path d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/>
                        </svg>
                    </x-slot>
                    <x-slot name="action">
                        <div class="w-full bg-secondary-800 rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                            <button class="focus:outline-none" @click="showedCardIndex = 2;">Voir details</button>
                        </div>
                    </x-slot>
                </x-count-card>

                <x-count-card
                    title="Attestations antérieures"
                    subtitle="{{ $totalAttestationAnterior }}"
                    bg-color="bg-accent-900"
                    text-color="text-accent-900"
                    link="#"
                >
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z"/><path d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zm-1-2V4H5v16h14zM8 7h8v2H8V7zm0 4h8v2H8v-2zm0 4h5v2H8v-2z"/>
                        </svg>
                    </x-slot>
                    <x-slot name="action">
                        <div class="w-full bg-accent-900 rounded-b-lg p-2 text-sm text-center text-white font-semibold">
                            <button class="focus:outline-none" @click="showedCardIndex = 3;">Voir details</button>
                        </div>
                    </x-slot>
                </x-count-card>
            </div>

            <div x-show="showedCardIndex === 1;">
                <div class="grid gap-6 mb-8 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-yellow-300 mb-4 md:mb-auto">
                        <h2 class="font-bold">Jaunes servies et produites</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalYellowSucceddedScan }}
                        </p>
                    </div>
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-gray-300 mb-4 md:mb-auto">
                        <h2 class="font-bold">Brunes servies et produites</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalBrownSucceddedScan }}
                        </p>
                    </div>
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-green-400 mb-4 md:mb-auto">
                        <h2 class="font-bold">Vertes servies et produites</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalGreenSucceddedScan }}
                        </p>
                    </div>
                </div>
            </div>

            <div x-show="showedCardIndex === 2;">
                <div class="grid gap-6 mb-8 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-yellow-300 mb-4 md:mb-auto">
                        <h2 class="font-bold">Jaunes litigieuses</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalYellowScanMismatches }}
                        </p>
                    </div>
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-gray-300 mb-4 md:mb-auto">
                        <h2 class="font-bold">Brunes litigieuses</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalBrownScanMismatches }}
                        </p>
                    </div>
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-green-400 mb-4 md:mb-auto">
                        <h2 class="font-bold">Vertes litigieuses</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalGreenScanMismatches }}
                        </p>
                    </div>
                </div>
            </div>

            <div x-show="showedCardIndex === 3;">
                <div class="grid gap-6 mb-8 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-yellow-300 mb-4 md:mb-auto">
                        <h2 class="font-bold">Jaunes antérieures</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalYellowAttestationAnterior }}
                        </p>
                    </div>
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-gray-300 mb-4 md:mb-auto">
                        <h2 class="font-bold">Brunes antérieures</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalBrownAttestationAnterior }}
                        </p>
                    </div>
                    <div class="flex flex-col cursor-pointer bg-white rounded p-4 items-center shadow border-r-8 border-green-400 mb-4 md:mb-auto">
                        <h2 class="font-bold">Vertes antérieures</h2>
                        <p class="p-4 text-gray-600 text-4xl">
                            {{ $totalGreenAttestationAnterior }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
