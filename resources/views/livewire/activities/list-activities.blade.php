<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('activities.index') }}
</x-slot>

<section>
    <x-section-header title="Activités sur la plateforme"></x-section-header>

    <livewire:activities.activities-table />
</section>
