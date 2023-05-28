<?php

namespace App\Http\Livewire\Scan;

use App\Domain\Scan\Actions\UpdateReviewedAttestationAction;
use Domain\Attestation\Rules\AttestationExists;
use Domain\Scan\Models\Scan;
use Illuminate\Support\Carbon;
use Livewire\Component;

class HumanReview extends Component
{
    /**
     * The scan instance.
     *
     * @var Scan
     */
    public $scan;

    /**
     * The list of items that needs review.
     *
     * @var Illuminate\Support\Collection
     */
    public $mismatches = [];

    /**
     * The item being edited.
     *
     * @var Domain\Scan\Models\ScanMismatch
     */
    public $itemBeingReviewed;

    /**
     * The index of the item being edited.
     *
     * @var int
     */
    public $itemIndexBeingReviewed;

    /**
     * The state of the component.
     */
    public $state = [
        'attestation_number' => null,
        'insured_name' => null,
        'police_number' => null,
        'immatriculation' => null,
        'address' => null,
        'make' => null,
        'start_date' => null,
        'end_date' => null,
    ];

    public function mount(Scan $scan)
    {
        $this->scan = $scan;
        $this->mismatches = $this->scan->mismatches()->get();
        $this->itemBeingReviewed = $this->mismatches->first();
        $this->itemIndexBeingReviewed = 0;
        $this->setState();
    }

    public function getCanGoBackProperty()
    {
        return isset($this->mismatches[$this->itemIndexBeingReviewed - 1]);
    }

    public function getCanGoNextProperty()
    {
        return isset($this->mismatches[$this->itemIndexBeingReviewed + 1]);
    }

    public function goToNext()
    {
        $this->resetErrorBag();

        if (! $this->canGoNext) {
            return;
        }

        $newIndex = $this->itemIndexBeingReviewed + 1;
        $this->itemBeingReviewed = $this->mismatches[$newIndex];
        $this->itemIndexBeingReviewed = $newIndex;
        $this->setState();
    }

    public function goToPrevious()
    {
        $this->resetErrorBag();

        if (! $this->canGoBack) {
            return;
        }

        $newIndex = $this->itemIndexBeingReviewed - 1;
        $this->itemBeingReviewed = $this->mismatches[$newIndex];
        $this->itemIndexBeingReviewed = $newIndex;
        $this->setState();
    }

    public function save(UpdateReviewedAttestationAction $action)
    {
        $this->resetErrorBag();
        $this->validate([
            'state.attestation_number' => ['required', 'integer', 'min:0'],
            'state.police_number' => ['nullable'],
            'state.start_date' => ['nullable', 'date'],
            'state.end_date' => ['nullable', 'date'],
        ]);

        $action->execute($this->scan, array_merge($this->state, [
            'image_path' => $this->itemBeingReviewed->image_path,
        ]));

        if ($this->itemBeingReviewed) {
            $this->itemBeingReviewed->delete();
            $this->itemBeingReviewed = null;
            $this->mismatches->forget($this->itemIndexBeingReviewed);
        }

        if (! $this->canGoNext && ! $this->canGoBack) {
            return redirect()->route('scan.show', $this->scan);
        }

        if ($this->canGoNext) {
            $this->goToNext();
        } else {
            $this->goToPrevious();
        }

        $this->emit('saved');
    }

    public function render()
    {
        return view('livewire.scan.human-review', ['canGoBack' => $this->canGoBack, 'canGoNext' => $this->canGoNext]);
    }

    private function setState()
    {
        if ($this->itemBeingReviewed == null) {
            return;
        }

        $this->state = $this->itemBeingReviewed->withoutRelations()->toArray();
        $this->state['start_date'] = $this->state['start_date'] ? Carbon::parse($this->state['start_date'])->format('Y-m-d') : null;
        $this->state['end_date'] = $this->state['end_date'] ? Carbon::parse($this->state['end_date'])->format('Y-m-d') : null;
    }
}
