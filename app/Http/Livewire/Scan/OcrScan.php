<?php

namespace App\Http\Livewire\Scan;

use App\Domain\Scan\Actions\ValidateOcrScanAction;
use App\ViewModels\Scan\ScanFormViewModel;
use Domain\Attestation\Models\AttestationType;
use Domain\Scan\Actions\CreateScanAction;
use Domain\Scan\Models\Scan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;
use Support\Uploader;

/**
 * @property AttestationType $attestationType
 */
class OcrScan extends Component
{
    use WithFileUploads;

    use AuthorizesRequests;

    public $files = null;

    public $state = [
        'broker_id' => null,
        'attestation_type_id' => null,
        'attestation_state' => null,
    ];

    protected $listeners = [
        'broker_idUpdated' => 'setBroker',
    ];

    public function setBroker($payload)
    {
        $this->state['broker_id'] = $payload['value'];
    }

    public function getAttestationTypeProperty()
    {
        return AttestationType::find($this->state['attestation_type_id']);
    }

    public function runScan(CreateScanAction $action, ValidateOcrScanAction $validator)
    {
        $this->authorize('create', Scan::class);

        $this->resetErrorBag();

        $this->validate([
            'state.attestation_type_id' => ['required'],
            'state.broker_id' => ['required'],
            'files' => ['required', 'array'],
            'files.*' => ['mimes:pdf'],
        ]);

        $validator->execute($this->state);

        $action->execute(array_merge($this->state, [
            'type' => Scan::TYPE_OCR,
            'initiated_by' => auth()->id(),
            'fileNames' => Uploader::uploadMultiple($this->files, "attestations/{$this->attestationType->slug}"),
        ]));

        session()->flash('success', "L'opération a été démarrée avec succès !");

        return redirect()->route('scan.index');
    }

    public function render()
    {
        return view('livewire.scan.ocr-scan', new ScanFormViewModel(Scan::TYPE_OCR, auth()->user()));
    }
}
