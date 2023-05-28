<x-badge class="{{ $attestation->state->textColor() }} {{ $attestation->state->color() }}">
    {{ $attestation->state->label() }}
</x-badge>
