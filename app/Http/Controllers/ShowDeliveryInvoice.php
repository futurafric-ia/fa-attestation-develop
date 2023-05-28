<?php

namespace App\Http\Controllers;

use Domain\Delivery\Models\Delivery;
use Illuminate\Http\Request;
use PDF;

final class ShowDeliveryInvoice
{
    public function __invoke(Request $request, Delivery $delivery)
    {
        view()->share(['delivery' => $delivery,]);

        $pdf = PDF::loadView('pdf.delivery.invoice');

        if ($request->input('download')) {
            return $pdf->download();
        }

        return $pdf->inline();
    }
}
