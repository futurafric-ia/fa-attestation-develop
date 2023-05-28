<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('scan.show.attestations.index', $scan) }}
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
    <x-section-header title="Enregistrements réussis">
        <x-slot name="actions">
            <x-button-link class="bg-secondary-900 text-white hover:bg-opacity-75" href="{{ route('scan.show', $scan) }}">
                <x-slot name="prependIcon">
                    <x-heroicon-o-arrow-narrow-left class="w-6 h-6" />
                </x-slot>
                Retour
            </x-button-link>
        </x-slot>
    </x-section-header>

    <livewire:scan.scanned-attestations-table :scan="$scan" />
</section>
