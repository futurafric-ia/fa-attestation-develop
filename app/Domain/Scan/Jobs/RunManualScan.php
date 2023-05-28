<?php

namespace Domain\Scan\Jobs;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\States\Cancelled;
use Domain\Attestation\States\Returned;
use Domain\Scan\Events\ScanCompleted;
use Domain\Scan\Events\ScanCreated;
use Domain\Scan\Events\ScanFailed;
use Domain\Scan\Models\Scan;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RunManualScan implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    /**
     * @var Scan
     */
    public Scan $scan;
    public array $data;

    /**
     * Create a new job instance.
     *
     * @param Scan $scan
     * @param array $data
     */
    public function __construct(Scan $scan, array $data)
    {
        $this->scan = $scan;
        $this->data = $data;
    }

    public function handle()
    {
        ScanCreated::dispatch($this->scan);

        $this->scan->update(['total_count' => $this->data['quantity'], 'payload' => ['data' => $this->data['items']]]);

        DB::beginTransaction();

        if ($this->scan->hasState([Returned::$name, Cancelled::$name], 'attestation_state')) {
            $this->executeForRanges();
        } else {
            $this->executeForItems();
        }

        DB::commit();

        ScanCompleted::dispatch($this->scan);
    }

    public function failed(\Throwable $throwable)
    {
        ScanFailed::dispatch($this->scan, $throwable->getMessage());
    }

    private function executeForRanges(): void
    {
        foreach ($this->data['items'] as $range) {
            Attestation::cursor()
                ->whereBetween('attestation_number', [$range['range_start'], $range['range_end']])
                ->chunk(500)
                ->each(function ($chunk) {
                    $chunk->each(function ($attestation) {
                        $attestation->update(['last_scan_id' => $this->scan->id]);
                        $attestation->scans()->attach($this->scan);
                        $attestation->state->transitionTo($this->scan->attestation_state);
                    });
                });
        }
    }

    private function executeForItems(): void
    {
        foreach ($this->data['items'] as $item) {
            $attestation = Attestation::firstWhere('attestation_number', $item['attestation_number']);

            $attestation->update(array_merge($item, [
                'attestation_type_id' => $this->scan->attestation_type_id,
                'last_scan_id' => $this->scan->id,
            ]));

            $attestation->scans()->attach($this->scan);
            $attestation->state->transitionTo($this->scan->attestation_state);
        }
    }
}
