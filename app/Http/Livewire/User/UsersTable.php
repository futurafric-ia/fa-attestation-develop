<?php

namespace App\Http\Livewire\User;

use App\TableFilters\DepartmentFilter;
use App\TableFilters\TrashedFilter;
use App\TableFilters\UserRoleFilter;
use Domain\User\Actions\DeleteUserAction;
use Domain\User\Actions\RestoreUserAction;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UsersTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $userIdBeingDeleted;
    public $confirmingUserDeletion = false;

    public $userIdBeingRestored;
    public $confirmingUserRestoration = false;

    public array $filterNames = [
        'role' => 'Rôle',
        'department' => 'Département',
        'status' => 'Statut',
    ];

    public function query(): Builder
    {
        return User::filter($this->filters)->onlySahamUsers();
    }

    public function columns(): array
    {
        return [
            Column::make('Nom', 'full_name')->searchable(function ($builder, $term) {
                return $builder
                    ->orWhere('first_name', 'like', '%' . $term . '%')
                    ->orWhere('last_name', 'like', '%' . $term . '%');
            }),
            Column::make('Titre')->format(function ($value, $column, User $row) {
                return view('livewire.user._titre', ['user' => $row]);
            }),
            Column::make('Email')->searchable()->sortable(),
            Column::make('')->format(function ($value, $column, User $row) {
                return view('livewire.user._table-actions', ['user' => $row]);
            })->hideIf(auth()->user()->cannot('user.create')),
        ];
    }

    public function filters(): array
    {
        return [
            UserRoleFilter::$id => UserRoleFilter::make(),
            DepartmentFilter::$id => DepartmentFilter::make(),
            TrashedFilter::$id => TrashedFilter::make(),
        ];
    }

    public function modalsView(): string
    {
        return 'livewire.user._modals';
    }

    public function impersonate($userId)
    {
        activity('Connexion à un autre compte')
        ->performedOn(User::find($userId))
        ->withProperties([
            'event' => "Impersonate",
            'Impersonate' => [
                'author' => auth()->user()->full_name ?? "Guest",
            ],
        ])
        ->log(":properties.Impersonate.author s'est connecté avec le compte de " . User::find($userId)->full_name);

        Auth::user()->impersonate(User::find($userId));

        return redirect()->route(homeRoute());
    }

    public function confirmUserDeletion($userId)
    {
        $this->userIdBeingDeleted = $userId;
        $this->confirmingUserDeletion = true;
    }

    public function confirmUserRestoration($userId)
    {
        $this->userIdBeingRestored = $userId;
        $this->confirmingUserRestoration = true;
    }

    public function deleteUser(DeleteUserAction $action)
    {
        $user = User::find($this->userIdBeingDeleted);

        $action->execute($user);

        $this->confirmingUserDeletion = false;
        $this->userIdBeingDeleted = null;

        session()->flash('success', "L'utilisateur a été supprimé avec succès !");

        return redirect()->route('users.index');
    }

    public function restoreUser(RestoreUserAction $action)
    {
        $action->execute(User::withTrashed()->find($this->userIdBeingRestored));

        $this->confirmingUserRestoration = false;

        session()->flash('success', "L'utilisateur a été restauré avec succès !");

        return redirect()->route('users.index');
    }
}
