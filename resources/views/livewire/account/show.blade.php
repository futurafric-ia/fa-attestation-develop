<x-slot name="breadcrumbs">
    {{ Breadcrumbs::render('account.profile') }}
</x-slot>

<section>
    <x-section-header title="Modification du profil" />

    <livewire:account.update-profile-information-form />

    <x-section-border />

    <div class="mt-10 sm:mt-0">
        <livewire:account.update-password-form />
    </div>
</section>
