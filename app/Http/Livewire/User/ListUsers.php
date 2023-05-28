<?php

namespace App\Http\Livewire\User;

use Domain\User\Actions\DeleteUserAction;
use Domain\User\Actions\RestoreUserAction;
use Domain\User\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ListUsers extends Component
{
    use AuthorizesRequests;

    public $userIdBeingDeleted;
    public $confirmingUserDeletion = false;

    public $userIdBeingRestored;
    public $confirmingUserRestoration = false;

    protected $listeners = [
        'confirming-delete-model' => 'confirmUserDeletion',
        'confirming-restore-model' => 'confirmUserRestoration',
    ];

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

        $this->authorize('delete', $user);

        $action->execute($user);

        $this->confirmingUserDeletion = false;

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

    public function render()
    {
        return view('livewire.user.list-users');
    }
}
