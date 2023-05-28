<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('settings.show') }}
</x-slot>

<section>
    <x-action-message on="brokerMemberAdded">
        <x-alert class="alert-success">
            Un nouvel utilisateur à été ajouté à votre organisation !
        </x-alert>
    </x-action-message>

    <x-action-message on="brokerMemberUpdated">
        <x-alert class="alert-success">
            Les modifications ont bien été enregistrées !
        </x-alert>
    </x-action-message>

    <x-action-message on="brokerMemberRemoved">
        <x-alert class="alert-success">
            Le membre à été bien été retiré de votre organisation !
        </x-alert>
    </x-action-message>

    <x-section-header title="Paramètres"></x-section-header>

    <livewire:backend.update-information-form>

</section>
