<?php

namespace Domain\Scan\Actions;

use Domain\Attestation\States\Cancelled;
use Domain\Attestation\States\Returned;
use Domain\Scan\Events\ScanCompleted;
use Domain\Scan\Events\ScanCreated;
use Domain\Scan\Events\ScanFailed;
use Domain\Scan\Jobs\ApplyIndexerResults;
use Domain\Scan\Jobs\ConvertPdfToImage;
use Domain\Scan\Jobs\QueryIndexer;
use Domain\Scan\Jobs\RunManualScan;
use Domain\Scan\Jobs\SetupAzure;
use Domain\Scan\Models\Scan;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

final class CreateScanAction
{
    private ValidateScanRangeAction $validator;

    public function __construct(ValidateScanRangeAction $validator)
    {
        $this->validator = $validator;
    }

    public function execute(array $data)
    {
        if (
            $data['type'] === Scan::TYPE_MANUAL
            && in_array($data['attestation_state'], [Returned::$name, Cancelled::$name], true)
        ) {
            $this->validator->execute($data['items'], $data['quantity']);
        }

        $scan = Scan::create([
            'attestation_type_id' => $data['attestation_type_id'],
            'source' => $data['source'] ?? Scan::SOURCE_INTERNAL,
            'broker_id' => $data['broker_id'],
            'type' => $data['type'],
            'initiated_by' => $data['initiated_by'],
            'attestation_state' => $data['attestation_state'],
            'resource_name' => date('dmYHis'),
        ]);

        ScanCreated::dispatch($scan);

        if ($scan->isOcr()) {
            $outputPath = config('saham.storage.output_path') . "/{$scan->attestationType->slug}/{$scan->resource_name}";

            $batch = Bus::batch([
                [
                    new ConvertPdfToImage($scan->getKey(), $data['fileNames'], $outputPath),
                    new SetupAzure($scan->getKey()),
                    new QueryIndexer($scan->getKey()),
                    new ApplyIndexerResults($scan->getKey()),
                ],
            ])->name('RunOcrScan')->then(function (Batch $batch) use ($scan) {
                ScanCompleted::dispatch($scan);
            })->catch(function (Batch $batch, \Throwable $e) use ($scan) {
                ScanFailed::dispatch($scan, $e->getMessage());
            })->dispatch();

            $scan->update(['batch_id' => $batch->id]);
        } elseif ($scan->isManual()) {
            RunManualScan::dispatch($scan, ['items' => $data['items'], 'quantity' => $data['quantity']]);
        }

        return $scan->fresh();
    }
}
