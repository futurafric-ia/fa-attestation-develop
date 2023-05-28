<?php

namespace App\Http\Livewire\Account;

use Domain\User\Actions\UpdatePasswordAction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
    public $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    protected $rules = [
        'state.current_password' => ['required', 'password'],
        'state.password' => ['required'],
        'state.password_confirmation' => ['required', 'same:state.password'],
    ];


    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updatePassword(UpdatePasswordAction $action)
    {
        $this->resetErrorBag();
        $this->validate();

        $action->execute($this->user, $this->state);

        $this->state = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->emit('saved');

        Auth::logout();

        return redirect()->to('/login');
    }

    public function render()
    {
        return view('livewire.account.update-password-form');
    }
}
