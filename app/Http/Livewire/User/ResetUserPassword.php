<?php

namespace App\Http\Livewire\User;

use Domain\User\Actions\ResetUserPasswordAction;
use Domain\User\Models\User;
use Livewire\Component;

class ResetUserPassword extends Component
{
    public $user;
    public $uuid;
    public $state = [
        'email' => null,
        'password' => null,
    ];

    public function resetUser()
    {
        $this->validate([
            'state.email' => ['required','exists:users,email'],
            'state.password' => ['required'],
        ]);

        $this->user = User::firstWhere('email', $this->state['email']);

        app(ResetUserPasswordAction::class)->execute($this->user, $this->state['password']);

        session()->flash('success', "Le mot de passe a été réinitilisalisé avec succès!");

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user.reset-user-password');
    }
}
