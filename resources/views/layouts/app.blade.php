<x-base-layout>
    @if(session('impersonated_by'))
        <x-alert title="Info" type="info">
            Vous êtes connectés en tant que {{ auth()->user()->roles()->first()->name }}.
            Pour retourner sur votre compte <strong><a href="{{ route('impersonate_leave') }}" class="button">cliquez ici</a></strong>
        </x-alert>
    @endif
    <div id="app" class="custom-scrollbar flex h-screen">
        <x-sidebar />

        <div class="flex flex-col flex-1 w-full">

            <x-navbar notification>
                <x-slot name="profileDropdown">
                    <div class="p-2 text-gray-600">
                        <div class="flex">
                            <a class="cursor-pointer inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                href="{{ route('account.profile') }}">
                                <x-heroicon-o-adjustments class="w-4 h-4 mr-3" />
                                <span>Modifier mon profil</span>
                            </a>
                        </div>
                        @if ($logged_in_user->isBroker())
                            <div class="flex">
                                <a class="cursor-pointer inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                    href="{{ route('settings.show') }}">
                                    <x-heroicon-o-users class="w-4 h-4 mr-3" />
                                    <span>Paramètres</span>
                                </a>
                            </div>
                        @endif
                        @if ($logged_in_user->isSuperAdmin())
                            <div class="flex">
                                <a class="cursor-pointer inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                    href="{{ route('backend.settings.index') }}">
                                    <x-heroicon-o-users class="w-4 h-4 mr-3" />
                                    <span>Paramètres</span>
                                </a>
                            </div>
                        @endif
                        <div class="flex">
                            <a class="cursor-pointer inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                <span>Se déconnecter</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </x-slot>
            </x-navbar>

            <hr class="shadow" />

            <main class="h-full overflow-y-auto">
                <div>
                    @isset($breadcrumbs)
                        <div class="flex justify-between items-center bg-gray-50 p-4 shadow">
                            <div>
                                {{ $breadcrumbs }}
                            </div>
                            @isset($breadcrumbsLinks)
                                {{ $breadcrumbsLinks }}
                            @endisset
                        </div>
                    @endisset

                    <div>
                        @include('shared.flasher')
                    </div>

                    {{-- limit width of the content --}}
                    @isset($breadcrumbs)
                        <div class="my-4 md:mx-auto sm:px-6 md:px-12 md:py-5" id="content">
                            {{ $slot }}
                        </div>
                    @else
                        <div class="mb-4" id="content">
                            {{ $slot }}
                        </div>
                    @endisset
                </div>
            </main>
        </div>
    </div>
</x-base-layout>
