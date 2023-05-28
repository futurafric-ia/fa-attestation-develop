<a
    @click="dropdownOpen = !dropdownOpen"
    class="relative block rounded-full bg-gray-300 p-1 border-1 border-transparent text-primary-800 cursor-pointer  hover:text-white hover:bg-primary-900 focus:outline-none focus:text-white focus:bg-teal-800 transition duration-150 ease-in-out"
>
    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>
    @if($notifications->count())
        <span
            class="absolute top-0 right-0 text-white bg-red-600 text-xs text-center font-semibold rounded-full h-4 w-4">
        {{ $notifications->count() }}
    </span>
    @endif
</a>

<div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10"></div>

<div x-show="dropdownOpen" class="absolute right-0 mt-2 bg-white rounded-md shadow overflow-hidden z-20"
     style="width:30rem;" x-cloak>
    @if($notifications->count())
        <div class="text-right leading-tight text-sm text-gray-700 px-4 py-2">
            <a href="{{ route('notifications.markAllAsRead') }}">Marquer tout comme lu</a>
        </div>
        <div>
            @foreach ($notifications as $notification)
                @if ($notification->data['type'] === 'new_request')
                    <a
                        href="{{ route('notifications.markAsRead', ['id' => $notification->id, 'redirectTo' => route('request.show', $notification->data['request_id'])]) }}"
                        class="flex flex-col px-4 py-3 border-b hover:bg-gray-100"
                    >
                        <span>
                            Vous avez reçu une nouvelle demande de <span class="font-bold">{{ $notification->data['broker_name'] }}</span>
                        </span>
                        <span class="text-primary-500 text-sm self-end mt-2">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </a>
                @endif
                @if ($notification->data['type'] === 'request_approved')
                    <a
                        href="{{ route('notifications.markAsRead', ['id' => $notification->id, 'redirectTo' => route('request.show', $notification->data['request_id'])]) }}"
                        class="flex flex-col px-4 py-3 border-b hover:bg-gray-100"
                    >
                        <span>
                            Vous avez une demande en attente de validation de <span class="font-bold">{{ $notification->data['broker_name'] }}</span>
                        </span>
                        <span class="text-primary-500 text-sm self-end mt-2">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </a>
                @endif
                @if ($notification->data['type'] === 'request_validated')
                    <a
                        href="{{ route('notifications.markAsRead', ['id' => $notification->id, 'redirectTo' => route('request.show', $notification->data['request_id'])]) }}"
                        class="flex flex-col px-4 py-3 border-b hover:bg-gray-100"
                    >
                        <span>
                            Vous avez une demande en attente de livraison de <span class="font-bold">{{ $notification->data['broker_name'] }}</span>
                        </span>
                        <span class="text-primary-500 text-sm self-end mt-2">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </a>
                @endif
                @if ($notification->data['type'] === 'request_delivered')
                    <a
                        href="{{ route('notifications.markAsRead', ['id' => $notification->id, 'redirectTo' => route('request.show', $notification->data['request_id'])]) }}"
                        class="flex flex-col px-4 py-3 border-b hover:bg-gray-100"
                    >
                        <span>
                            Votre demande d'attestations de type <span class="font-bold">{{ $notification->data['attestation_type_name'] }}</span> a été traitée
                        </span>
                        <span class="text-primary-500 text-sm self-end mt-2">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </a>
                @endif
                @if ($notification->data['type'] === 'request_rejected')
                    <a
                        href="{{ route('notifications.markAsRead', ['id' => $notification->id, 'redirectTo' => route('request.show', $notification->data['request_id'])]) }}"
                        class="flex flex-col px-4 py-3 border-b hover:bg-gray-100"
                    >
                        <span class="">{{ $notification->data['content'] }}</span>
                        <span class="text-primary-500 text-sm self-end mt-1">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </a>
                @endif
                @if ($notification->data['type'] === 'export_ready')
                    <a
                        href="{{ route('notifications.markAsRead', ['id' => $notification->id, 'redirectTo' => route('download.export', $notification->data['file_name'])]) }}"
                        class="flex flex-col items-center px-4 py-3 border-b hover:bg-gray-100"
                    >
                        <span>
                            Votre export s'est terminé avec succès. Veuillez cliquez sur la notification pour telecharger le fichier.
                        </span>
                        <span class="text-primary-500 text-sm self-end">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                @endif
            @endforeach
        </div>
    @else
        <div class="py-5 text-sm text-center">
            <span class="font-bold">Vous n'avez pas de notifications non lues</span>
        </div>
    @endif

    <a href="{{ route('notifications.index') }}" class="block bg-primary-600 text-white text-center font-bold py-2">Toutes les notifications</a>
</div>
