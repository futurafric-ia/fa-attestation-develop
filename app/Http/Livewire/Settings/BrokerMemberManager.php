<?php

namespace App\Http\Livewire\Settings;

use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\UpdateUserAction;
use Domain\User\Actions\UpdateUserProfileAction;
use Domain\User\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class BrokerMemberManager extends Component
{
    /**
     * The broker instance.
     *
     * @var mixed
     */
    public $broker;

    public $addBrokerMemberForm = [
        'first_name' => null,
        'last_name' => null,
        'email' => null,
    ];

    public $editBrokerMemberForm = [
        'first_name' => null,
        'last_name' => null,
        'email' => null,
    ];

    /**
     * Indicates if the application is confirming if a broker member is being added.
     *
     * @var int|null
     */
    public $addingBrokerMember = false;

    /**
     * Indicates if the application is confirming if a broker member is being edited.
     *
     * @var int|null
     */
    public $editingBrokerMember = false;

    /**
     * The ID of the broker member being managed.
     *
     * @var int|null
     */
    public $brokerMemberIdBeingEdited = null;

    /**
     * Indicates if the application is confirming if a broker member should be removed.
     *
     * @var bool
     */
    public $confirmingBrokerMemberRemoval = false;

    /**
     * The ID of the broker member being removed.
     *
     * @var int|null
     */
    public $brokerMemberIdBeingRemoved = null;


    public function mount($broker)
    {
        $this->broker = $broker;
    }

    public function addBrokerMember(CreateUserAction $createUserAction, UpdateUserAction $updateUserAction)
    {
        $this->validate([
            'addBrokerMemberForm.first_name' => ['required'],
            'addBrokerMemberForm.last_name' => ['required'],
            'addBrokerMemberForm.email' => ['required', 'email'],
        ]);

        $payload = array_merge($this->addBrokerMemberForm, ['type' => User::TYPE_BROKER, 'department_id' => $this->broker->department_id]);

        /**
         * Si l'utilisateur avait déja été crée auparavant et supprimé, on le restore et on l'assigne au nouvel intermédiaire.
         * Dans le cas contraire, on crée un nouvel utilisateur.
         */
        if (User::withTrashed()->where('email', $this->addBrokerMemberForm['email'])->exists()) {
            $newUser = User::withTrashed()->firstWhere('email', $this->addBrokerMemberForm['email']);

            if ($newUser && $newUser->type !== User::TYPE_BROKER) {
                throw ValidationException::withMessages([
                    'addBrokerMemberForm.email' => ['Cette adresse e-mail est utilisé par un autre compte.']
                ]);
            }

            if ($newUser && $newUser->type === User::TYPE_BROKER && $newUser->current_broker_id) {
                throw ValidationException::withMessages([
                    'addBrokerMemberForm.email' => ['Ce utilisateur est enregistré au compte d\'un autre intermédiaire.']
                ]);
            }

            $newUser->restore();
            $updateUserAction->execute($newUser, $payload);
        } else {
            $newUser = $createUserAction->execute($payload);
        }

        $this->broker->addUser($newUser);

        $this->addingBrokerMember = false;

        $this->broker = $this->broker->fresh();

        $this->addBrokerMemberForm = [
            'first_name' => null,
            'last_name' => null,
            'email' => null,
        ];

        $this->emit('brokerMemberAdded')->up();
    }

    public function confirmEditingBrokerMember($userId)
    {
        $this->editingBrokerMember = true;
        $this->brokerMemberIdBeingEdited = $userId;
        $this->editBrokerMemberForm = User::find($userId)->withoutRelations()->toArray();
    }

    public function stopEditingBrokerMember()
    {
        $this->editingBrokerMember = false;
        $this->brokerMemberIdBeingEdited = null;
        $this->reset(['editBrokerMemberForm']);
    }

    public function updateBrokerMember(UpdateUserProfileAction $action)
    {
        $brokerMember = User::find($this->brokerMemberIdBeingEdited);

        $this->validate([
            'editBrokerMemberForm.first_name' => ['required'],
            'editBrokerMemberForm.last_name' => ['required'],
            'editBrokerMemberForm.email' => ['required', 'email', Rule::unique('users', 'email')->ignoreModel($brokerMember)],
        ]);

        $action->execute($brokerMember, $this->editBrokerMemberForm);

        $this->editingBrokerMember = false;
        $this->brokerMemberIdBeingEdited = null;
        $this->reset(['editBrokerMemberForm']);

        $this->broker = $this->broker->fresh();

        $this->emit('brokerMemberUpdated')->up();
    }

    public function confirmBrokerMemberRemoval($userId)
    {
        $this->confirmingBrokerMemberRemoval = true;

        $this->brokerMemberIdBeingRemoved = $userId;
    }

    public function removeBrokerMember()
    {
        $member = User::findOrFail($this->brokerMemberIdBeingRemoved);

        $this->broker->removeUser($member);
        $member->delete();

        $this->confirmingBrokerMemberRemoval = false;
        $this->brokerMemberIdBeingRemoved = null;

        $this->broker = $this->broker->fresh();

        $this->emit('brokerMemberRemoved')->up();
    }

    public function render()
    {
        return view('livewire.settings.broker-member-manager');
    }
}
