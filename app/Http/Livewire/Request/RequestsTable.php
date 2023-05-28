<?php

namespace App\Http\Livewire\Request;

use App\TableFilters\AttestationTypeFilter;
use App\TableFilters\RequestStateFilter;
use Domain\Request\Models\Request;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RequestsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public array $filterNames = [
        'status' => 'Statut'
    ];

    public function query(): Builder
    {
        return Request::allowedForUser(auth()->user())
            ->filter($this->filters)
            ->with('broker', 'attestationType')
            ->orderBy('created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Emise le')->format(fn ($value, $column, Request $row) => $row->created_at->format('d/m/Y H:i')),
            Column::make('Intermédiaire', 'broker.name')->searchable(function ($builder, $term) {
                return $builder->orWhereHas('broker', function ($query) use ($term) {
                    return $query->where('name', 'like', '%' . $term . '%');
                });
            })->hideIf(auth()->user()->isBroker()),
            Column::make('Type d\'imprimés', 'attestationType.name'),
            Column::make('Quantité demandée', 'quantity')->sortable(),
            Column::make('Statut')->format(function ($value, $column, Request $row) {
                return view('livewire.request._status', ['request' => $row]);
            })->hideIf(isset($this->filters['status'])),
            Column::make('Actions')->format(function ($value, $column, Request $row) {
                return view('livewire.request._table-actions', ['request' => $row]);
            }),
        ];
    }

    public function filters(): array
    {
        return [
            RequestStateFilter::$id => RequestStateFilter::make(),
            AttestationTypeFilter::$id => AttestationTypeFilter::make(),
        ];
    }
}
