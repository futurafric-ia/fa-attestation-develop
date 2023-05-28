<?php

namespace Domain\Request\States;

use Domain\Request\Models\Request;
use Spatie\ModelStates\Transition;

class PendingToCancelled extends Transition
{
    private $request;

    private $message;

    public function __construct(Request $request, ?string $message = '')
    {
        $this->request = $request;
        $this->message = $message;
    }

    public function handle(): Request
    {
        $this->request->state = new Cancelled($this->request);
        $this->request->aborted_at = now();
        $this->request->reason = $this->message;

        $this->request->save();

        return $this->request;
    }
}
