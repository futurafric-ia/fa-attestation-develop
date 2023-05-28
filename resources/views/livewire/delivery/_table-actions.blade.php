<div class="flex space-x-2">
    @if($delivery->isDone() && ($logged_in_user->isBroker() || $logged_in_user->can('delivery.create')))
    <a href="{{ route('delivery.showInvoice', $delivery->id) }}" class="block">
        <x-heroicon-o-receipt-tax class="text-primary-700 dark:text-gray-200 h-4 w-4" id="show-invoice-{{$delivery->id}}" />
        <x-tooltip content="Consulter le bordereau" placement="top" append-to="#show-invoice-{{$delivery->id}}" />
    </a>
    @endif
</div>
