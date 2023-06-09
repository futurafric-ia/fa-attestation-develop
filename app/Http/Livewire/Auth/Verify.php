<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Verify extends Component
{
    public function resend()
    {
        $this->redirectIfVerified();

        Auth::user()->sendEmailVerificationNotification();

        session()->flash('resent', true);
    }

    public function mount()
    {
        $this->redirectIfVerified();
    }

    protected function redirectIfVerified()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect(homeRoute());
        }
    }

    public function render()
    {
        return view('livewire.auth.verify')->layout('layouts.auth');
    }
}
