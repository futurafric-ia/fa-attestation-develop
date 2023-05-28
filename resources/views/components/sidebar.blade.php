<!-- Menu Above Medium Screen -->
<div class="bg-primary-800 w-64 min-h-screen overflow-y-auto hidden md:block shadow text-sm">
    <!-- Brand Logo / Name -->
    <div class="flex items-center px-4 py-2">
        <a href="{{ route('dashboard') }}" class="h-20 mx-auto">
            <x-logo class="h-full w-full"/>
        </a>
    </div>
    <!-- @end Brand Logo / Name -->

    <div class="py-2 mt-6">
        <ul>
            <x-sidebar-link route="dashboard" :active="activeClass((Route::is('dashboard.*') || Route::is('backend.dashboard')))">
                <x-slot name="icon">
                    <x-heroicon-o-home
                        class="w-6 h-6 group-hover:text-white {{ activeClass((Route::is('dashboard.*') || Route::is('backend.dashboard')), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Tableau de bord') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="users.index"
                :active="activeClass(Route::is('users.*'))"
                permission="view_backend"
            >
                <x-slot name="icon">
                    <x-heroicon-o-user-group
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('users.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Utilisateurs') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="brokers.index"
                :active="activeClass(Route::is('brokers.*'))"
                permission="view_backend"
            >
                <x-slot name="icon">
                    <x-heroicon-o-office-building
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('brokers.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Intermédiaires') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="users.index"
                :active="activeClass(Route::is('users.*'))"
                permission="user.list"
            >
                <x-slot name="icon">
                    <x-heroicon-o-user-group
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('users.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Utilisateurs') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="brokers.index"
                :active="activeClass(Route::is('brokers.*'))"
                permission="broker.list"
            >
                <x-slot name="icon">
                    <x-heroicon-o-office-building
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('brokers.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Intermédiaires') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="suppliers.index"
                :active="activeClass(Route::is('suppliers.*'))"
                permission="supplier.list"
            >
                <x-slot name="icon">
                    <x-heroicon-o-truck
                            class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('suppliers.*'), 'text-white', 'text-white') }}" />
                </x-slot>
                {{ __('Fournisseurs') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="activities.index"
                :active="activeClass(Route::is('activities.*'))"
                permission="activity.list"
            >
                <x-slot name="icon">
                    <x-heroicon-o-calendar
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('activities.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Activités') }}
            </x-sidebar-link>

            <x-sidebar-link
                :route="$logged_in_user->can('delivery.create') ? 'request.stats' : 'request.index'"
                permission="request.list"
                :active="activeClass(Route::is('request.*'))"
            >
                <x-slot name="icon">
                    <x-heroicon-o-mail
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('request.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Demandes') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="delivery.index"
                :active="activeClass(Route::is('delivery.*'))"
                permission="delivery.list"
            >
                <x-slot name="icon">
                    <x-heroicon-o-truck
                            class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('delivery.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Livraisons') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="supply.index"
                :active="activeClass(Route::is('supply.*'))"
                permission="supply.list"
            >
                <x-slot name="icon">
                    <x-heroicon-o-truck
                            class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('supply.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Approvisionnements') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="attestation.index"
                :active="activeClass(Route::is('attestation.index'))"
                permission="attestation.list"
            >
                <x-slot name="icon">
                    <x-heroicon-o-collection
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('attestation.index'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Attestations') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="attestation.anterior.index"
                :active="activeClass(Route::is('attestation.anterior.*'))"
                permission="attestation.list_anterior"
            >
                <x-slot name="icon">
                    <x-heroicon-o-collection
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('attestation.anterior.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Attestations antérieures') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="stock.index"
                :active="activeClass(Route::is('stock.*'))"
                permission="stock.show"
            >
                <x-slot name="icon">
                    <x-heroicon-o-archive
                        class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('stock.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Stock Disponible') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="scan.index"
                :active="activeClass(Route::is('scan.*'))"
                permission="attestation.scan"
            >
                <x-slot name="icon">
                    <x-heroicon-o-printer
                            class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('scan.*'), 'text-white', 'text-white') }}"/>
                </x-slot>
                {{ __('Scans') }}
            </x-sidebar-link>

            <x-sidebar-link
                route="report.index"
                :active="activeClass(Route::is('report.*'))"
                permission="analytics.show"
            >
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('report.*'), 'text-white', 'text-white') }}">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                </x-slot>
                {{ __('Rapports') }}
            </x-sidebar-link>

             <x-sidebar-link
                route="reports.delivery"
                :active="activeClass(Route::is('reports.delivery'))"
                permission="analytics.show"
            >
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 group-hover:text-white {{ activeClass(Route::is('reports.delivery'), 'text-white', 'text-white') }}">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </x-slot>
                {{ __('Statistiques') }}
            </x-sidebar-link>

            <div
                class="relative"
                x-data="{ open: '{{ Route::is('backend.departments.index') || Route::is('backend.imprimes.index') || Route::is('backend.roles.index')|| Route::is('backend.users.reset') }}' }"
                @click.away="open = false" @close.stop="open = false"
            >
                <div @click="open = ! open">
                    <x-sidebar-link permission="view_backend">
                        <x-slot name="icon">
                            <x-heroicon-o-cog
                                class="w-6 h-6 group-hover:text-white {{ activeClass((Route::is('backend.departments.index') || Route::is('backend.imprimes.index') || Route::is('backend.roles.index') || Route::is('backend.users.reset')), 'text-white', 'text-white') }}"/>
                        </x-slot>
                        <div class="flex items-center justify-between">
                            <span class="inline-block mr-1">{{ __('Configuration') }}</span>
                            <span class="inline-block pl-8">
                                <x-heroicon-o-chevron-down class="w-4 h-4 border-teal-400"/>
                            </span>
                        </div>
                    </x-sidebar-link>
                </div>

                <div
                    class="absolute z-50 w-full rounded-md origin-top-right right-0"
                    x-cloak
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                >
                    <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-transparent">
                        <div class="py-1 pl-3">
                            <x-sidebar-link
                                route="backend.departments.index"
                                :active="activeClass(Route::is('backend.departments.index'))"
                                permission="view_backend"
                            >
                                <x-slot name="icon">
                                    <x-heroicon-o-chevron-right
                                        class="w-5 h-5 hover:font-extrabold hover:text-blue-900 hover:text-3xl {{ activeClass(Route::is('backend.departments.index'), 'text-white', 'text-white') }}"/>
                                </x-slot>
                                {{ __('Départements') }}

                            </x-sidebar-link>
                        </div>
                        <div class="py-1 pl-3">
                            <x-sidebar-link
                                route="backend.imprimes.index"
                                :active="activeClass(Route::is('backend.imprimes.index'))"
                                permission="view_backend"
                            >
                                <x-slot name="icon">
                                    <x-heroicon-o-chevron-right
                                        class="w-5 h-5 hover:font-extrabold hover:text-blue-900 hover:text-3xl {{ activeClass(Route::is('backend.imprimes.index'), 'text-white', 'text-white') }}"/>
                                </x-slot>
                                {{ __('Type d\'imprimés') }}
                            </x-sidebar-link>
                        </div>
                        <div class="py-1 pl-3">
                            <x-sidebar-link
                                route="backend.roles.index"
                                :active="activeClass(Route::is('backend.roles.index'))"
                                permission="view_backend"
                            >
                                <x-slot name="icon">
                                    <x-heroicon-o-chevron-right
                                        class="w-5 h-5 hover:font-extrabold hover:text-blue-900 hover:text-3xl {{ activeClass(Route::is('backend.roles.index'), 'text-white', 'text-white') }}"/>
                                </x-slot>
                                {{ __('Rôles et permissions') }}
                            </x-sidebar-link>
                        </div>
                        <div class="py-1 pl-3">
                            <x-sidebar-link
                                route="backend.users.reset"
                                :active="activeClass(Route::is('backend.users.reset'))"
                                permission="view_backend"
                            >
                                <x-slot name="icon">
                                    <x-heroicon-o-chevron-right
                                        class="w-5 h-5 hover:font-extrabold hover:text-blue-900 hover:text-3xl {{ activeClass(Route::is('backend.users.reset'), 'text-white', 'text-white') }}"/>
                                </x-slot>
                                {{ __('Réinitialisation mot de passe') }}
                            </x-sidebar-link>
                        </div>
                    </div>
                </div>
            </div>
        </ul>
    </div>
</div>
<!-- @end Menu Above Medium Screen -->
