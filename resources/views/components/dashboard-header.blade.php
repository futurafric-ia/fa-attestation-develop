<div class="flex bg-gray-50 space-x-4 shadow text-primary-800 mb-4 px-5 py-2">
    <div class="w-3/4">
        <div class="mx-3">
            <h1 class="font-semibold text-2xl leading-10">Bonjour, {{ $logged_in_user->first_name }}</h1>
            <div class="mt-2">
                <ul class="inline-flex items-center text-gray-800 font-semibold">
                    <li class="flex mr-4 ">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="fill-current w-5 h-5 text-gray-9sss00">
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path
                                d="M3.783 2.826L12 1l8.217 1.826a1 1 0 0 1 .783.976v9.987a6 6 0 0 1-2.672 4.992L12 23l-6.328-4.219A6 6 0 0 1 3 13.79V3.802a1 1 0 0 1 .783-.976zM5 4.604v9.185a4 4 0 0 0 1.781 3.328L12 20.597l5.219-3.48A4 4 0 0 0 19 13.79V4.604L12 3.05 5 4.604zM12 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm-4.473 5a4.5 4.5 0 0 1 8.946 0H7.527z" />
                        </svg>
                        <span class="text-sm font-medium ml-1">
                            {{ $logged_in_user->main_role_name }}
                            @if ($logged_in_user->main_role->hasDepartment())
                                @if ($logged_in_user->isBroker())
                                    ({{ $logged_in_user->currentBroker->department->name }})
                                @else
                                    ({{ $logged_in_user->main_department_name }})
                                @endif
                            @endif
                        </span>
                    </li>
                    <li class="flex items-center">
                        <x-heroicon-o-office-building class="w-5 h-5 text-gray-900" />
                        <span class="text-sm font-medium ml-1">{{ $logged_in_user->isBroker() ? broker()->name : 'FA E-Attestation' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="w-1/4 flex justify-end items-center">
        <div>
            <div class="flex justify-center font-bold text-sm">
                @isset($actions)
                    {{ $actions }}
                @endisset
            </div>
        </div>
    </div>
</div>
