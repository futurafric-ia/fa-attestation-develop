<?php

namespace App\Http\Livewire\Account;

use Domain\User\Actions\UpdateUserProfileAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $photo = null;

    public $state = [];

    public function mount()
    {
        $this->state = Auth::user()->withoutRelations()->toArray();
    }

    public function getRules()
    {
        return [
            'state.first_name' => ['required'],
            'state.last_name' => ['required'],
            'state.email' => ['required', 'email', Rule::unique('users', 'email')->ignoreModel(Auth::user())],
            'state.address' => ['nullable', 'string', 'max:255'],
            'state.contact' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image','max:10240'],
        ];
    }

    public function updateProfile(UpdateUserProfileAction $action)
    {
        $this->resetErrorBag();

        $this->validate();

        $action->execute(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            return redirect()->route('account.show');
        }

        $this->emit('saved');
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function render()
    {
        return view('livewire.account.update-profile-information-form');
    }
}
