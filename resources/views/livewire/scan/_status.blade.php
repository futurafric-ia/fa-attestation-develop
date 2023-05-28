@if($scan->jobBatch === null)
    <x-badge class="{{ $scan->state->textColor() }} {{ $scan->state->color() }}">
        {{ $scan->state->label() }}
    </x-badge>
@elseif($scan->jobBatch->hasFailures())
    <x-badge class="text-gray-50 bg-red-500">
        Echoué
    </x-badge>
@elseif($scan->jobBatch->hasPendingJobs())
    <x-badge class="text-gray-50 bg-orange-500">
        En cours: {{ $scan->jobBatch->progress() }} %
    </x-badge>
@elseif($scan->jobBatch->finished())
    <x-badge class="text-gray-50 bg-green-500">
        Terminé
    </x-badge>
@endif

