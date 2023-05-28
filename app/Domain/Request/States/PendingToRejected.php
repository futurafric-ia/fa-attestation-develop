<?php

namespace Domain\Request\States;

use Domain\Request\Models\Request;
use Spatie\ModelStates\Transition;

class PendingToRejected extends Transition
{
    private $request;

    private $message;

    public function __construct(Request $request, ?string $message = null)
    {
        $this->request = $request;
        $this->message = $message;
    }

    public function handle(): Request
    {
        $this->request->state = new Rejected($this->request);
        $this->request->aborted_at = now();
        $this->request->reason = $this->message ?: $this->guessRejectionReason();

        $this->request->save();

        return $this->request;
    }

    private function guessRejectionReason(): ?string
    {
        if (! $this->request->broker->hasConsumedMinimumQuota($this->request->attestation_type_id)) {
            return sprintf(
                "Moins de %s%s du stock d'attestation %s a été consommé.",
                $this->request->broker->minimum_consumption_percentage,
                '%',
                $this->request->attestationType->name
            );
        }

        return $this->request->broker->notes;
    }
}
