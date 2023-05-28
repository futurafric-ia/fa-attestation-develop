<?php

namespace App\Http\Livewire\Request;

use Domain\Request\Actions\CommentRequestAction;
use Domain\Request\Events\RequestCancelled;
use Domain\Request\Models\Request;
use Domain\Request\States\Cancelled;
use Livewire\Component;

class ShowRequest extends Component
{
    public $request;
    public $notes = [];
    public $state = [
        'note' => null,
        'reason' => null,
    ];

    public function mount(Request $request)
    {
        $this->request = $request;
        $this->notes = $this->request->discussions;
    }

    public function cancel()
    {
        $this->request->state->transitionTo(Cancelled::class, $this->state['reason']);

        RequestCancelled::dispatch($this->request);
    }

    public function comment(CommentRequestAction $action)
    {
        $this->validate(['state.note' => ['required', 'string', 'max:255']]);

        $this->notes[] = $action->execute($this->request, [
            'note' => $this->state['note'],
            'user_id' => auth()->user()->id,
        ]);

        $this->reset(['state']);
    }

    public function render()
    {
        return view('livewire.request.show-request');
    }
}
