<?php

namespace App\Http\Livewire\Scan;

use App\Exports\ScanReportCsv;
use Domain\Scan\Models\Scan;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ScansTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $refresh = 5000;

    public function exportCsv()
    {
        return \Excel::download(new ScanReportCsv(), 'scan.csv');
    }

    public function query(): Builder
    {
        return Scan::query()
            ->allowedForUser(auth()->user())
            ->with('broker', 'attestationType')
            ->latest();
    }

    public function columns(): array
    {
        return [
            Column::make('Effectué le', 'created_at')->sortable(),
            Column::make('Intermédiaire', 'broker.name'),
            Column::make("Type d'imprimés", 'attestationType.name'),
            Column::make('Total documents', 'total_count')->sortable(),
            Column::make('Statut')->format(function ($value, $column, Scan $row) {
                return view('livewire.scan._status', ['scan' => $row]);
            }),
            Column::make('')->format(function ($value, $column, Scan $row) {
                return view('livewire.scan._table-actions', ['scan' => $row]);
            }),
        ];
    }
}
