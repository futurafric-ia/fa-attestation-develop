<?php

namespace App\Http\Controllers;

use Domain\Request\Models\Request;
use Illuminate\Http\Request as HttpRequest;
use PDF;

final class ShowRequestReport
{
    public function __invoke(HttpRequest $request)
    {
        view()->share([
            'title' => 'Rapport des demandes',
            'items' => Request::filter($request->query())->allowedForUser(auth()->user())->with('attestationType', 'broker.department')->get(),
            'columns' => [
                'Intermédiaire' => fn ($item) => $item->broker->name . " ({$item->broker->code})",
                'Département' => 'broker.department.name',
                'Date demande' => fn ($item) => $item->created_at->format('d/m/Y'),
                "Type d'imprimés" => 'attestationType.name',
            ],
        ]);

        $pdf = PDF::loadView('pdf.report');

        if ($request->input('download')) {
            return $pdf->download('demande_' . date('d-m-Y') . '.pdf');
        }

        return $pdf->inline();
    }
}
