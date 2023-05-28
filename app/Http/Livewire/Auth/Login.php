<?php

namespace App\Http\Livewire\Auth;

use Domain\User\Events\UserLoggedIn;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Livewire\Component;

class Login extends Component
{
    use AuthenticatesUsers;

    public $email;

    public $password;

    public $remember = false;

    public function authenticate()
    {
        return $this->login(new Request([
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
        ]));
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth');
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['active' => true]);
    }

    protected function sendLoginResponse(Request $request)
    {
        session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return redirect()->route(homeRoute());
    }

    protected function authenticated(Request $request, $user)
    {
        event(new UserLoggedIn($user));
    }
}
