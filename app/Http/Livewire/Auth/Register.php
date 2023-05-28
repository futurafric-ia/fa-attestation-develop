<?php

namespace App\Http\Livewire\Auth;

use Domain\User\Actions\CreateUserAction;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Register extends Component
{
    public $firstName = '';

    public $lastName = '';

    public $email = '';

    public $password = '';

    public $passwordConfirmation = '';

    public function register(CreateUserAction $createUserAction)
    {
        abort(401);

        $this->validate([
            'firstName' => ['required'],
            'lastName' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'same:passwordConfirmation'],
        ]);

        $user = $createUserAction->execute([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        event(new Registered($user));

        Auth::login($user, true);

        redirect(route(homeRoute()));
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.auth');
    }
}
