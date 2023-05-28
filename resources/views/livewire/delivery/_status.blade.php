@if($delivery->jobBatch === null)
    <x-badge class="{{ $delivery->state->textColor() }} {{ $delivery->state->color() }}">
        {{ $delivery->state->label() }}
    </x-badge>
@elseif($delivery->jobBatch->hasFailures())
    <x-badge class="text-gray-50 bg-red-500">
        Echoué
    </x-badge>
@elseif($delivery->jobBatch->hasPendingJobs())
    <x-badge class="text-gray-50 bg-orange-500">
        En cours
    </x-badge>
@elseif($delivery->jobBatch->finished())
    <x-badge class="text-gray-50 bg-green-500">
        Terminé
    </x-badge>
@endif
