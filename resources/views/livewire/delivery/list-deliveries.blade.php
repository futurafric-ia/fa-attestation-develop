<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('delivery.index') }}
</x-slot>

<section>
    <x-section-header title="Livraisons" />

    <livewire:delivery.deliveries-table />

</section>
