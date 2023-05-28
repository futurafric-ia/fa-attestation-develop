<?php

namespace App\Http\Livewire\User;

use Domain\User\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Livewire\Component;

class ShowUser extends Component
{
    use AuthorizesRequests;

    public User $user;

    public function regenerateApiKey()
    {
        $this->user->update(['api_token' => Str::random(60)]);

        $this->user->refresh();

        $this->emit('api_key_saved');
    }

    public function render()
    {
        return view('livewire.user.show-user');
    }
}
