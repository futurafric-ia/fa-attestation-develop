<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('scan.show', $scan) }}
</x-slot>

<section>
    <div class="md:mx-auto md:py-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Vue d'ensemble</h2>
        </div>
        <div class="mt-2">
            <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
                <x-count-card
                    title="Documents chargés"
                    subtitle="{{ $scan->total_count }}"
                    bg-color="bg-gray-400"
                    text-color="text-gray-400"
                    link="#"
                    link-title=""
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill="none" d="M0 0H24V24H0z" />
                            <path
                                d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2zm0 2c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8zm0 1c1.018 0 1.985.217 2.858.608L13.295 7.17C12.882 7.06 12.448 7 12 7c-2.761 0-5 2.239-5 5 0 1.38.56 2.63 1.464 3.536L7.05 16.95l-.156-.161C5.72 15.537 5 13.852 5 12c0-3.866 3.134-7 7-7zm6.392 4.143c.39.872.608 1.84.608 2.857 0 1.933-.784 3.683-2.05 4.95l-1.414-1.414C16.44 14.63 17 13.38 17 12c0-.448-.059-.882-.17-1.295l1.562-1.562zm-2.15-2.8l1.415 1.414-3.724 3.726c.044.165.067.338.067.517 0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2c.179 0 .352.023.517.067l3.726-3.724z" />
                        </svg>
                    </x-slot>
                </x-count-card>
                <x-count-card
                    title="Enregistrements réussis"
                    subtitle="{{ $scan->success_count }}"
                    bg-color="bg-green-400"
                    text-color="tex-green-400"
                    link="{{ route('scan.show.attestations.index', $scan) }}"
                >
                    <x-slot name="icon">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="check w-8 h-8">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd">
                            </path>
                        </svg>
                    </x-slot>
                </x-count-card>
                <x-count-card
                    title="Attestations litigieuses"
                    subtitle="{{ $scan->mismatches_count }}"
                    bg-color="bg-blue-900"
                    text-color="tex-blue-900"
                    link="{{ route('scan.show.mismatches.index', $scan) }}"
                >
                    <x-slot name="icon">
                        <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path
                                d="M21 9.5v3c0 2.485-4.03 4.5-9 4.5s-9-2.015-9-4.5v-3c0 2.485 4.03 4.5 9 4.5s9-2.015 9-4.5zm-18 5c0 2.485 4.03 4.5 9 4.5s9-2.015 9-4.5v3c0 2.485-4.03 4.5-9 4.5s-9-2.015-9-4.5v-3zm9-2.5c-4.97 0-9-2.015-9-4.5S7.03 3 12 3s9 2.015 9 4.5-4.03 4.5-9 4.5z" />
                        </svg>
                    </x-slot>
                </x-count-card>
            </div>
        </div>
    </div>
</section>
