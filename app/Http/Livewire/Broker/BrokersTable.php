<?php

namespace App\Http\Livewire\Broker;

use App\TableFilters\DepartmentFilter;
use App\TableFilters\TrashedFilter;
use Domain\Broker\Actions\DeleteBrokerAction;
use Domain\Broker\Actions\RestoreBrokerAction;
use Domain\Broker\Models\Broker;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class BrokersTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $brokerIdBeingDeleted;
    public $confirmingBrokerDeletion = false;

    public $brokerIdBeingRestored;
    public $confirmingBrokerRestoration = false;

    public function query(): Builder
    {
        return Broker::filter($this->filters)
            ->with('department')->withCount('users')
            ->orderBy('created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'code')->searchable()->sortable(),
            Column::make('Raison Social', 'name')->searchable()->sortable(),
            Column::make('Département', 'department.name')->searchable(function ($builder, $term) {
                return $builder->orWhereHas('department', function ($query) use ($term) {
                    return $query->where('name', 'like', '%' . $term . '%');
                });
            }),
            Column::make('Date de création')->format(fn ($value, $column, Broker $row) => $row->created_at->format('d/m/Y')),
            Column::make('Nbr. Utilisateurs', 'users_count'),
            Column::make('')->format(function ($value, $column, Broker $row) {
                return view('livewire.broker._table-actions', ['broker' => $row]);
            })->hideIf(auth()->user()->cannot('broker.create')),
        ];
    }

    public function filters(): array
    {
        return [
            DepartmentFilter::$id => DepartmentFilter::make(),
            TrashedFilter::$id => TrashedFilter::make(),
        ];
    }

    public function modalsView(): string
    {
        return 'livewire.broker._modals';
    }

    public function confirmBrokerDeletion($brokerId)
    {
        $this->brokerIdBeingDeleted = $brokerId;
        $this->confirmingBrokerDeletion = true;
    }

    public function confirmBrokerRestoration($brokerId)
    {
        $this->brokerIdBeingRestored = $brokerId;
        $this->confirmingBrokerRestoration = true;
    }

    public function deleteBroker(DeleteBrokerAction $action)
    {
        $action->execute(Broker::find($this->brokerIdBeingDeleted));
        $this->confirmingBrokerDeletion = false;

        session()->flash('success', "L'intermédiaire a été supprimé avec succès !");

        return redirect()->route('brokers.index');
    }

    public function restoreBroker(RestoreBrokerAction $action)
    {
        $action->execute(Broker::withTrashed()->find($this->brokerIdBeingRestored));

        $this->confirmingBrokerRestoration = false;

        session()->flash('success', "L'intermédiaire a été restauré avec succès !");

        return redirect()->route('brokers.index');
    }
}
