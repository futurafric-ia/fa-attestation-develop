@section('title', 'Tableau de bord')

<section>
    <x-dashboard-header></x-dashboard-header>

    <div class="md:mx-auto sm:px-6 md:px-12 md:py-4" x-data="{ showAttestationDashboard: true }">
        <div class="flex justify-end mb-4 items-center">
            <div class="rounded-full bg-white flex justify-between text-xs">
                <button @click="showAttestationDashboard = true" class="rounded-full py-1 px-3 cursor-pointer focus:outline-none" :class="{'bg-secondary-300 font-medium': showAttestationDashboard }">Par attestation</button>
                <button @click="showAttestationDashboard = false" class="rounded-full py-1 px-3 cursor-pointer focus:outline-none" :class="{'bg-secondary-300 font-medium': !showAttestationDashboard }">Par intermédiaire</button>
            </div>
        </div>

        <div x-show="showAttestationDashboard">
            <livewire:dashboards.manager-attestation-dashboard />
        </div>

        <div x-show="! showAttestationDashboard">
            <livewire:dashboards.manager-broker-dashboard />
        </div>
    </div>
</section>


