<?php

namespace App\Http\Livewire\Activities;

use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\Activitylog\Models\Activity;

class ActivitiesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public function query(): Builder
    {
        return Activity::query()->latest()
            ->where('log_name', '!=', 'attestation')
            ->orWhereNotIn('description', ['updated', 'created', 'deleted'])
            ->with('causer')
            ->orderBy('created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Sujet', 'log_name')->sortable()->searchable(),
            Column::make('Description', 'description')->sortable()->searchable(),
            Column::make('Date')->format(fn ($value, $column, Activity $row) => $row->created_at->format('d/m/Y H:i')),
        ];
    }
}
