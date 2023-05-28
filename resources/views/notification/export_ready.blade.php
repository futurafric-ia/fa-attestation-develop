@php($data = $notification->data)

<tr>
    <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm leading-5 text-gray-600">
        <div class="flex items-center">
            <svg fill="currentColor" viewBox="0 0 20 20" class="w-5 h-5 mr-4">
                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path>
            </svg>
                Votre export s'est terminé avec succès. Vous pouvez telecharger le fichier.
        </div>
    </td>

    <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm leading-5 text-gray-600">
        {{ $notification->created_at->diffForHumans() }}
    </td>

    <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200 text-sm leading-5 text-gray-600 text-right">
        <div class="flex justify-end">
            <a href="{{ route('notifications.markAsRead', ['id' => $notification->id, 'redirectTo' => route('download.export', $notification->data['file_name'])]) }}"
               class="mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </a>
            <button wire:click="markAsRead('{{ $notification->id }}')" class="text-lio-500">
                <svg viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd"
                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </td>
</tr>
