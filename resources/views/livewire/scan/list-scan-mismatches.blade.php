<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('scan.show.mismatches.index', $scan) }}
</x-slot>

<x-slot name="breadcrumbsLinks">
    <div class="flex space-x-2  text-sm">
        <a href="{{ route('scan.show.attestations.index', $scan) }}"
            class="{{ Route::is('scan.show.attestations.index') ? 'text-blue-600' : '' }}">
            Enregistrements réussis
        </a>
        <a href="{{ route('scan.show.mismatches.index', $scan) }}"
            class="{{ Route::is('scan.show.mismatches.index') ? 'text-blue-600' : '' }}">
            Attestations litigieuses
        </a>
    </div>
</x-slot>

<section>
    <x-section-header title="Attestations litigieuses">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" href="{{ route('scan.show', $scan) }}">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
            @if ($shouldReview)
                <x-button-link class="btn-secondary" href="{{ route('scan.show.mismatches.humanReview', $scan) }}">
                    Faire la revue
                    <x-slot name="appendIcon">
                        <x-heroicon-o-arrow-right class="w-5 h-5 text-white"></x-heroicon-o-arrow-right>
                    </x-slot>
                </x-button-link>
            @endif
        </x-slot>
    </x-section-header>

    <livewire:scan.scan-mismatches-table :scan="$scan" />
</section>
