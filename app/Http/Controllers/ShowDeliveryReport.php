<?php

namespace App\Http\Controllers;

use Domain\Delivery\Models\Delivery;
use Illuminate\Http\Request;
use PDF;

final class ShowDeliveryReport
{
    public function __invoke(Request $request)
    {
        view()->share([
            'title' => 'Rapport des livraisons',
            'items' => Delivery::filter($request->query())->allowedForUser(auth()->user())->with('attestationType', 'broker', 'request')->get(),
            'columns' => [
                'Intermédiaire' => 'broker.name',
                'Date de livraison' => fn ($item) => $item->created_at->format('d/m/Y'),
                "Type d'imprimés" => 'attestationType.name',
                'Quantité livrée' => 'request.quantity_delivered',
            ],
        ]);

        $pdf = PDF::loadView('pdf.report');

        if ($request->input('download')) {
            return $pdf->download('livraisons_' . date('d-m-Y') . '.pdf');
        }

        return $pdf->inline();
    }
}
